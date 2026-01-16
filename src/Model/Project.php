<?php

namespace Model;

class Project extends AbstractModel {

    public string $name;
    public array $origins;
    public ?Email $email;
    public ?Telegram $telegram;
    public ?Captcha $captcha;
    public \List\ElementList $elements;

    function import(array $data) {

        $elements = [];
        foreach ($data['elements'] ?? [] as $element) {
            $elements[] = Element::instance($element);
        }
        $data['elements'] = $elements;

        return parent::import($data);
    }
}
