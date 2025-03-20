<?php

namespace Enum;

enum FieldError {

    case required;
    case empty_string;
    case text_min_length;
    case text_max_length;
    case email_address;
    case not_in_haystack;
}
