<?php

namespace App\Lib\Validator;

use App\Lib\Http\Request;
use App\Lib\Error\ImageFileError;

class ValidateImageFile
{
    public static function exec(Request $req): ImageFileError|null
    {
        $img_error = null;

        if (!isset($req->files['images'])) return new ImageFileError(message: "ファイルを入力してください。");

        $allowed_types = ['image/png', 'image/jpeg', 'image/gif'];
        $max_size = 1048576;
        foreach ($req->files['images']['error'] as $key => $error) {
            if ($error == UPLOAD_ERR_OK) {
                $file_name = $req->files['images']['name'][$key];
                $file_size = $req->files['images']['size'][$key];
                $file_tmp_name = $req->files['images']['tmp_name'][$key];

                if (!file_exists($file_tmp_name)) {
                    global $img_error;
                    $img_error = new ImageFileError(message: "ファイルが選択されていません。");
                    break;
                }

                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime_type = finfo_file($finfo, $file_tmp_name);
                finfo_close($finfo);
                if (!in_array($mime_type, $allowed_types)) {
                    global $img_error;
                    $img_error = new ImageFileError(message: "{$file_name}のファイル形式が異なります。");
                    break;
                }

                if ($file_size > $max_size) {
                    global $img_error;
                    $img_error = new ImageFileError(message: "{$file_name}のファイルサイズが大きすぎます。1MB以内のファイルを選択してください。");
                    break;
                }
            }
        }

        return $img_error;
    }
}
