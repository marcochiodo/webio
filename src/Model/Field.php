<?php

namespace Model;

class Field extends AbstractModel {

    public string $name;
    public ?bool $required;
    public ?int $min_length;
    public ?int $max_length;
    public ?string $regex;
}
