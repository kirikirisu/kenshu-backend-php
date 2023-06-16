<?php
namespace App\Lib\Validator;

use App\Lib\Error\InputError;

class ValidatePost
{
    public static function exec(string $title, string $body, string $main_image): array
    {
        /** @var InputError[] $error_list */
        $error_list = [];

        if ($title === "") {
            $error_list[] = new InputError("タイトルを入力してください。", "title");
        }
        if ($body === "") {
            $error_list[] = new InputError("本文を入力してください。", "body");
        }
        if ($main_image === "") {
            $error_list[] = new InputError("メイン画像を選択してください", "image");
        }

        return $error_list;
    }
}
