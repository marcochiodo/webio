<?php

namespace Model\FormTelegram;

class TelegramConfig extends \Model\AbstractModel {

    public \List\FormTelegram\TelegramRecipientList $recipient_list;
    public string $body;
    public ?\Enum\TelegramParseMode $parse_mode;
}
