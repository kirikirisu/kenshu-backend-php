<?php

namespace App\Lib\Error;


class ImageFileError
{
    public function __construct(
        public int         $type,
        public string|null $file_name = null,
        public string      $message)
    {
    }
}

abstract class FileErrorType
{
    const NOT_SELECTED = 1;
    const NOT_ALLOWED = 2;
    const LARGE_FILE = 3;

    private function __construct()
    {
    }
}
