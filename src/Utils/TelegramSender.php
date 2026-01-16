<?php

namespace Utils;

use App;
use Exception\BaseException;
use Model\Telegram;
use mrblue\framework\Utils\Request;

class TelegramSender {

    function __construct(
        private readonly Telegram $Telegram,
        private readonly array $data
    ) {
    }

    function createMessage(): string {

        $message_template = file_get_contents('config/telegram-template.html');
        $Request = Request::getGlobalInstance();

        $fields = [];

        foreach ($this->data as $key => $value) {
            $fields[] =
                ('<strong>' . App::$Translation->t('default_fields_names/' . $key, $this->Telegram->language) . '</strong>') .
                (strlen($value) > 40 ? ("\n<pre>" . $value . '</pre>') : ' ' . $value);
        }

        return strtr($message_template, [
            '{site_name}' => $Request->host,
            '{fields}' => implode("\n", $fields),
        ]);
    }

    function send(): bool {

        $HttpClient = App::getTelegramBotHttpClient($this->Telegram->bot);

        $message = $this->createMessage();

        try {
            $HttpClient->post('sendMessage', [
                'form_params' => [
                    'chat_id' => $this->Telegram->to,
                    'text' => $message,
                    'parse_mode' => 'HTML'
                ]
            ]);
            return true;
        } catch (\Throwable $th) {
            BaseException::log($th);
            return false;
        }
    }
}
