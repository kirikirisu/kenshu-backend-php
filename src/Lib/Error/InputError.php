<?php

namespace App\Lib\Error;

use App\Lib\Validator\Enum\InputErrorType;

class InputError
{
    public function __construct(
        public int $type,
        public string $field)
    {
    }
}
