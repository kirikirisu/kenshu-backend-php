<?php
namespace App\Lib\Errors;

class InputError
{
    public function __construct(public string $message, public string $field)
    {
    }
}
