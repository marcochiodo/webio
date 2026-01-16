<?php

namespace Helper;

class Translation {

    const DEFAULT_LANGUAGE = 'en';

    const ALLOWED_LANGUAGES = ['en', 'it'];

    protected array $translations = [];

    function __construct(public readonly string $language) {
    }

    static function createFromHttpHeader(?string $header): self {

        if (! $header) {
            return new self(self::DEFAULT_LANGUAGE);
        }

        $substr = strtolower(substr($header, 0, 2));
        if (in_array($substr, self::ALLOWED_LANGUAGES)) {
            $language = $substr;
        } else {
            $language = self::DEFAULT_LANGUAGE;
        }

        return new self($language);
    }

    function t(string $key, ?string $language = null): string {

        $language = $language ?? $this->language;
        $translations = $this->getTranslations();
        $path_parts = explode('/', $key);

        $current = $translations;
        $last_part = $path_parts[count($path_parts) - 1];
        do {
            $part = array_shift($path_parts);
            $current = $current[$part] ?? null;
            if (! $current) {
                return $last_part;
            }
        } while ($path_parts);

        return $current[$language] ?? $current[self::DEFAULT_LANGUAGE] ?? $last_part;
    }

    private function getTranslations(): array {
        if ($this->translations) {
            return $this->translations;
        }

        $this->translations = require 'config/translations.php';
        return $this->translations;
    }
}
