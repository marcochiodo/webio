<?php

namespace Utils;

use App;
use Exception\BaseException;
use Model\Email;
use Model\Field;
use Model\Form;
use mrblue\framework\Utils\Request;

class EmailSender {

    function __construct(
        private readonly Email $Email,
        private readonly array $data
    ) {
    }

    function createMessage(): array {

        list($subject_template, $message_template) = explode("\n\n", file_get_contents('config/email-template.txt'), 2);
        $Request = Request::getGlobalInstance();

        $fields = [];

        foreach ($this->data as $key => $value) {
            $fields[] = App::$Translation->t('default_fields_names/' . $key, $this->Email->language) .
                (strlen($value) > 40 ? "\n" . $value : ': ' . $value);
        }

        return [
            'subject' => strtr($subject_template, [
                '{site_name}' => $Request->host,
            ]),
            'message' => strtr($message_template, [
                '{site_name}' => $Request->host,
                '{fields}' => implode("\n", $fields),
            ])
        ];
    }

    function send(): bool {

        $user = '';
        $Mailer = App::getSmtp($this->Email->smtp, $user);

        $message = $this->createMessage();

        $from_name = explode('@', $user)[0];
        $EmailAddress = new \Symfony\Component\Mime\Address($user, $from_name);

        $EmailMsgg = new \Symfony\Component\Mime\Email();
        $EmailMsgg->from($EmailAddress);

        $EmailMsgg->subject($message['subject']);
        $EmailMsgg->text($message['message']);
        $EmailMsgg->to($this->Email->to);

        try {
            $Mailer->send($EmailMsgg);
            return true;
        } catch (\Throwable $th) {
            BaseException::log($th);
            return false;
        }
    }
}
