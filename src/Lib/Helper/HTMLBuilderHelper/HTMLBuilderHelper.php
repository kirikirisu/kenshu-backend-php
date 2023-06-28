<?php

namespace App\Lib\Helper\HTMLBuilderHelper;

class HTMLBuilderHelper
{
    /**
     * @param UIMaterial[] $ui_material_list
     */
    public static function mixUiMaterial(string $base, array $ui_material_list): string
    {
        $fragment = "";

        for ($i = 0; $i < count($ui_material_list); $i++) {
            $slot = "%" . $ui_material_list[$i]->slot . "%";
            if ($i === 0) $fragment = str_replace($slot, $ui_material_list[$i]->replacement, $base);

            $fragment = str_replace($slot, $ui_material_list[$i]->replacement, $fragment);
        }

        return $fragment;
    }
}
