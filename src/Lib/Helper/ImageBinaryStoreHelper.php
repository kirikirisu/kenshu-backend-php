<?php

namespace App\Lib\Helper;

use App\Model\Dto\Image\StoredImageDto;

const DIR_FOR_IMAGE_BINARY = "/assets/images/";

class StoreImgFilePath {
    public function __construct(
        public string  $absolute_file_path,
        public string $root_relative_path)
    {
    }
}

class ImageBinaryStoreHelper
{
    public static function getStoreAbsDirPath(): string
    {
        return $_SERVER['DOCUMENT_ROOT'] . DIR_FOR_IMAGE_BINARY;
    }

    public static function generateUniqueFileName(string $filename): string
    {
        return uniqid(pathinfo($filename, PATHINFO_FILENAME)) . "." . pathinfo($filename, PATHINFO_EXTENSION);
    }

    public static function getStoreFilePath(string $filename): StoreImgFilePath
    {
        $unique_file_name = self::generateUniqueFileName($filename);

        $absolute_file_path = self::getStoreAbsDirPath() . $unique_file_name;
        $root_relative_path = DIR_FOR_IMAGE_BINARY . $unique_file_name;

        return new StoreImgFilePath(absolute_file_path: $absolute_file_path, root_relative_path: $root_relative_path);
    }

    public static function storeToDisk(string $filename, string $src_file_path): StoreImgFilePath
    {
        $path_list = self::getStoreFilePath(filename: $filename);
        move_uploaded_file($src_file_path, $path_list->absolute_file_path);

        return $path_list;
    }
}
