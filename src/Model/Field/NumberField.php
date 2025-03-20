<?php

namespace Model\Field;

class NumberField extends AbstractField {

    public bool $allow_string = true;
    public bool $allow_float = false;
    public ?int $min;
    public ?int $max;
    public ?int $step;
}
