<?php

namespace Model\Field;

abstract class AbstractField extends \Model\AbstractModel {

    public \Enum\FieldType $field_type;
    public ?bool $required = false;
    public ?bool $allow_empty_string = true;

    static function getFinalClass(array $data): string {
        return match ($data['field_type'] ?? '') {
            \Enum\FieldType::text->value => TextField::class,
            \Enum\FieldType::enum->value => EnumField::class,
            \Enum\FieldType::number->value => NumberField::class,
            \Enum\FieldType::email_address->value => EmailAddressField::class,
            \Enum\FieldType::phone_number->value => PhoneNumberField::class
        };
    }

    //abstract function validate(): mixed;
}
