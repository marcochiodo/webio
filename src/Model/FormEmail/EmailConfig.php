<?php

namespace Model\FormEmail;

class EmailConfig extends \Model\AbstractModel {

    public \List\FormEmail\EmailRecipientList $recipient_list;
    public string $subject;
    public string $body;
}
