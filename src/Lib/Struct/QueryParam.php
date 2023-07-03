<?php
namespace App\Lib\Struct;

class QueryParam {
    public function __construct(
        public string $key,
        public string $value
    )
    {
    }
}

