<?php

namespace App\Lib\Validator;

use App\Lib\Struct\QueryParam;
use App\Lib\Struct\UIMaterial;
use App\Lib\Validator\Enum\InputErrorType;

class ErrorMessage
{
    public static function getErrorUIMaterial(QueryParam $param): UIMaterial|null
    {
        if ($param->key === "title" && (int)$param->value === InputErrorType::REQUIRED) {
           return new UIMaterial(slot: "invalid_title", replacement: '<p class="mt-1 text-pink-600">タイトルを入力してください。</p>');
        } else if ($param->key === "body" && (int)$param->value === InputErrorType::REQUIRED) {
           return new UIMaterial(slot: "invalid_body", replacement: '<p class="mt-1 text-pink-600">本文を入力してください。</p>');
        } else if ($param->key === "main_image" && (int)$param->value === InputErrorType::REQUIRED) {
           return new UIMaterial(slot: "invalid_main_image", replacement: '<p class="mt-1 text-pink-600">メイン画像を選択してください。</p>');
        } else if ($param->key === "refererStatus") {
           return new UIMaterial(slot: "referer_error", replacement: '<p class="mt-1 text-pink-600">他のユーザーの投稿は、編集、更新、削除できません。</p>');
        }

        return null;
    }
}

