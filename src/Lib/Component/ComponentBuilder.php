<?php

namespace App\Lib\Component;

use App\Lib\Struct\UIMaterial;

class ComponentBuilder
{
    public static function defineComponent(string $content_path, array $props): string
    {
        $base_html = file_get_contents(self::templateAbsPath() . $content_path);
        return self::mixUiMaterial(base: $base_html, ui_material_list: $props);
    }

    private static function templateAbsPath(): string
    {
        return dirname($_SERVER['DOCUMENT_ROOT']) . '/view/html/';
    }

    /**
     * @param UIMaterial[] $ui_material_list
     */
    private static function mixUiMaterial(string $base, array $ui_material_list): string
    {
        $fragment = "";

        for ($i = 0; $i < count($ui_material_list); $i++) {
            $slot = "%" . $ui_material_list[$i]->slot . "%";
            if ($i === 0) $fragment = str_replace($slot, $ui_material_list[$i]->replacement, $base);

            $fragment = str_replace($slot, $ui_material_list[$i]->replacement, $fragment);
        }

        return self::cleanRestSlot($fragment);
    }

    private static function cleanRestSlot(string $text): string
    {
        $pattern = '/%(.*?)%/';
        return preg_replace($pattern, '', $text);
    }
}
