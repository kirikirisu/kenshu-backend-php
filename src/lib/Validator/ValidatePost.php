<?php
require_once(dirname(__DIR__, 1). "/Errors/InputError.php");

class ValidatePost
{
    public static function exec(string $title, string $body): array
    {
        /** @var InputError[] $error_list */
        $error_list = [];

        if ($title === "") {
            $error_list[] = new InputError("タイトルを入力してください。", "title");
        }
        if ($body === "") {
            $error_list[] = new InputError("本文を入力してください。", "body");
        }

        return $error_list;
    }
}
