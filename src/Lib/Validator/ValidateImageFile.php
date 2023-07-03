<?php

namespace App\Lib\Validator;

use App\Lib\Error\FileErrorType;
use App\Lib\Error\ImageFileError;

class ValidateImageFile
{
    public static function exec(mixed $file_list): array
    {
        if (!isset($file_list)) return [new ImageFileError(type: FileErrorType::NOT_SELECTED, message: "ファイルが選択されていません。")];

        $file_error_list = [];

        foreach ($file_list['error'] as $key => $error) {
            if ($error == UPLOAD_ERR_OK) {
                $file_name = $file_list['name'][$key];
                $file_size = $file_list['size'][$key];
                $tmp_file_name = $file_list['tmp_name'][$key];

                $file_error = self::validateFile(tmp_file_name: $tmp_file_name, file_name: $file_name, size: $file_size);
                if (!is_null($file_error)) $file_error_list[] = $file_error;
            }
        }

        return $file_error_list;
    }

    public static function validateFile(string $tmp_file_name, string $file_name, string $size): ImageFileError|null
    {
        $allowed_types = ['image/png', 'image/jpeg', 'image/gif'];
        $max_size = 1048576;
        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        if (!file_exists($tmp_file_name)) {
            return new ImageFileError(type: FileErrorType::NOT_SELECTED, message: "ファイルが選択されていません。");
        }

        $mime_type = finfo_file($finfo, $tmp_file_name);
        finfo_close($finfo);
        if (!in_array($mime_type, $allowed_types)) {
            return new ImageFileError(type: FileErrorType::NOT_ALLOWED, file_name: $file_name, message: "{$file_name}のファイル形式は無効です。");
        }

        if ($size > $max_size) {
            return new ImageFileError(type: FileErrorType::LARGE_FILE, file_name: $file_name, message: "{$file_name}のファイルサイズは大きすぎます。1MB以内のファイルを選択してください。");
        }

        return null;
    }
}
