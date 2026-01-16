<?php

namespace Utils;

class Encryptor {

    const METHOD = 'aes-256-cbc';
    const HASH_METHOD = 'sha3-512';
    const HASH_LENGTH = 64;

    static function encrypt(string $data): string {

        $key = base64_decode(ENCRYPTION_KEY);

        $iv_length = openssl_cipher_iv_length(self::METHOD);
        $iv = openssl_random_pseudo_bytes($iv_length);

        $payload = openssl_encrypt($data, self::METHOD, $key, OPENSSL_RAW_DATA, $iv);
        $hash = hash_hmac('sha3-512', $payload, $key, TRUE);

        return base64_encode($iv . $hash . $payload);
    }

    static function decrypt(string $ciphertext): false|string {
        $key = base64_decode(ENCRYPTION_KEY);
        $mix = base64_decode($ciphertext);

        $iv_length = openssl_cipher_iv_length(self::METHOD);

        $iv = substr($mix, 0, $iv_length);
        $hash = substr($mix, $iv_length, self::HASH_LENGTH);
        $payload = substr($mix, $iv_length + self::HASH_LENGTH);

        $hash_check = hash_hmac(self::HASH_METHOD, $payload, $key, TRUE);

        if (!hash_equals($hash, $hash_check)) {
            return false;
        }

        return openssl_decrypt($payload, self::METHOD, $key, OPENSSL_RAW_DATA, $iv);
    }
}
