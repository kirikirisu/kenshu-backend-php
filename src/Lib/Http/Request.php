<?php

namespace App\Lib\Http;

use App\Lib\Struct\QueryParam;

class Request
{
    public function __construct(
        public string $method,
        public string $path,
        public mixed  $get,
        public mixed  $post,
        public mixed  $files
    )
    {
    }

    /**
     * @return QueryParam[]
     */
    public function parseQuery(): array
    {
        $query = $this->path;
        $param_list = [];

        // ? 以降の文字列を取り出す
        $queryString = substr($query, strpos($query, "?") + 1);

        $pairs = explode("&", $queryString);
        foreach ($pairs as $pair) {
            $components = explode("=", $pair);

            $param_list[] = new QueryParam(key: urldecode($components[0]), value: urldecode($components[1]));
        }

        return $param_list;
    }
}
