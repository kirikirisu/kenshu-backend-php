<?php

namespace App\Lib\Component;

use App\Lib\Struct\UIMaterial;

class Badge
{
    public static function render(string $tag_name): string
    {
        return ComponentBuilder::defineComponent(content_path: 'part/badge.html', props: [new UIMaterial(slot: "tag", replacement: $tag_name)]);
    }
}
