<?php
namespace App\Lib\Helper;

/**
 * @param string $text
 * @return string[]
 */
function marchal_array_from_object_string(string $text): array
{
    return explode(",", str_replace("}", "", str_replace("{", "", $text)));
}
