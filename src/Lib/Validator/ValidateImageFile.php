<?php

namespace App\Lib\Validator;

use App\Lib\Error\FileErrorType;
use App\Lib\Error\ImageFileError;
use App\Lib\Http\Request;

class ValidateImageFile
{
    /**
     * @param Request $req
     * @param string $target
     * @param bool $multi
     * @return ImageFileError[]
     */
    public static function exec(Request $req, string $target, bool $multi = false): array
    {
        if (!isset($req->files[$target])) return [new ImageFileError(type: FileErrorType::NOT_SELECTED, message: "ファイルが選択されていません。")];

        $file_error_list = [];
        $target_file = $req->files[$target];

        if ($multi) {
            foreach ($req->files['images']['error'] as $key => $error) {
                if ($error == UPLOAD_ERR_OK) {
                    $file_name = $req->files['images']['name'][$key];
                    $file_size = $req->files['images']['size'][$key];
                    $tmp_file_name = $req->files['images']['tmp_name'][$key];

                    $file_error = self::validateFile(tmp_file_name: $tmp_file_name, file_name: $file_name, size: $file_size);
                    if (!is_null($file_error)) $file_error_list[] = $file_error;
                }
            }
        } else {
            $error = self::validateFile(tmp_file_name: $target_file['tmp_name'], file_name: $target_file['name'], size: $target_file['size']);
            if (!is_null($error)) $file_error_list[] = $error;
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
