<?php

namespace App\Lib\Component;

use App\Lib\Struct\UIMaterial;

class Image
{

    /**
     * @param string $url
     * @param bool $isThumbnail
     * @return string
     */
    public static function render(string $url, bool $isThumbnail = false): string
    {
        $thumbnail_style = "border-8 border-orange-500";

        $ui_material_list = [];
        $ui_material_list[] = new UIMaterial(slot: "src", replacement: $url);
        if ($isThumbnail) $ui_material_list[] = new UIMaterial(slot: "thumbnail_style", replacement: $thumbnail_style);

        return ComponentBuilder::defineComponent(content_path: 'part/image.html', props: $ui_material_list);
    }
}
