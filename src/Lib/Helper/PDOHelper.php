<?php

namespace App\Lib\Helper;

class PDOHelper
{
    /**
     * @param string $text
     * @return string[]
     */
    public static function marchalArrayFromObjectString(string $text): array
    {
        return explode(",", str_replace("}", "", str_replace("{", "", $text)));
    }

    /**
     * @param string[] $string_list
     * @return string[]
     */
    public static function removeDuplicateString(array $string_list): array
    {
        return array_unique($string_list);
    }

    /**
     * @param string $text
     * @return string[]
     */
    public static function convertArrayAggResult(string $text): array
    {
        return self::removeDuplicateString(self::marchalArrayFromObjectString($text));
    }

    // from [1, 2, 3] to ?, ?, ?
    public static function generateInClausePlaceholder(array $id_list): string
    {
        return implode(',', array_fill(0, count($id_list), '?'));
    }
}
