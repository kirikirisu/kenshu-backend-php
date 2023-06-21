<?php
namespace App\Lib\Error;

class InputError
{
    public function __construct(public string $message, public string $field)
    {
    }
}
