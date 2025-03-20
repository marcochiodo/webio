<?php

namespace Enum;

enum FieldType: string {

    case text = 'text';
    case enum = 'enum';
    case number = 'number';
    case email_address = 'email_address';
    case phone_number = 'phone_number';
}
