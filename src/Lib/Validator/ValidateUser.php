<?php

namespace App\Lib\Validator;

use App\Lib\Error\InputError;

class ValidateUser
{
    /**
     * @param string $name
     * @param string $mail
     * @param string $password
     * @return InputError[]
     */
    public static function exec(string $name, string $mail, string $password): array
    {
        /** @var InputError[] $error_list */
        $error_list = [];

        if ($name === "") {
            $error_list[] = new InputError("名前を入力してください。", "name");
        }
        if ($mail === "") {
            $error_list[] = new InputError("メールアドレスを入力してください。", "mail");
        }
        if ($password === "") {
            $error_list[] = new InputError("パスワードを入力してください。", "password");
        }

        return $error_list;
    }
}
