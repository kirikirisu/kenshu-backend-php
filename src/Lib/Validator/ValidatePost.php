<?php

namespace App\Lib\Validator;

use App\Lib\Error\InputError;
use App\Lib\Validator\Enum\InputErrorType;

class ValidatePost
{
    /**
     * @param string $title
     * @param string $body
     * @param string $main_image
     * @return InputError[]
     */
    public static function exec(string $title, string $body, string $main_image): array
    {
        /** @var InputErrorType[] $error_list */
        $error_list = [];

        if ($title === "") {
            $error_list[] = new InputError(type: InputErrorType::REQUIRED, field: "title");
        }
        if ($body === "") {
            $error_list[] = new InputError(type:InputErrorType::REQUIRED, field: "body");
        }
        if ($main_image === "") {
            $error_list[] = new InputError(type: InputErrorType::REQUIRED, field: "main_image");
        }

        return $error_list;
    }
}
