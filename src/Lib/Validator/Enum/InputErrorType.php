<?php

namespace App\Lib\Validator\Enum;

use ReflectionClass;

abstract class InputErrorType
{
    const REQUIRED = 0;
    const DUPLICATED = 1;

    private function __construct()
    {
    }

    public static function getConstants(): array
    {
        $reflection = new ReflectionClass(static::class);
        return $reflection->getConstants();
    }
}
