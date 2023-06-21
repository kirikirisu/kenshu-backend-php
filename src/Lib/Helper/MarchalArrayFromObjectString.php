<?php

namespace App\Lib\Helper;

/**
 * @param string $text
 * @return string[]
 */
class MarchalArrayFromObjectString
{
    public static function exec(string $text)
    {
        return explode(",", str_replace("}", "", str_replace("{", "", $text)));
    }
}
