<?php
namespace App\Lib\Helper;

class PDOHelper
{
    /**
     * @param string $text
     * @return string[]
     */
    public static function marchalArrayFromObjectString(string $text)
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

    public static function convertArrayAggResult(string $text): array
    {
        return self::removeDuplicateString(self::marchalArrayFromObjectString($text));
    }
}
