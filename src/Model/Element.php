<?php

namespace Model;

use Enum\ElementType;

abstract class Element extends AbstractModel {

    public string $name;
    public ElementType $type;

    static function instance(array $data): self {
        return match ($data['type']) {
            ElementType::form->value => new Form($data)
        };
    }
}
