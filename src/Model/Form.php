<?php

namespace Model;

class Form extends AbstractModel {

    public ?FormEmail\EmailConfig $email_config;
    public ?FormTelegram\TelegramConfig $telegram_config;
    public \List\FieldList $field_list;
}
