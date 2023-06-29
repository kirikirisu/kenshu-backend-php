<?php

namespace App\Lib\Component;

use App\Lib\Struct\UIMaterial;

class BadgeList
{
    public static function render(array $tag_list): string
    {

        return ComponentBuilder::defineComponent(content_path: 'part/badge.html', props: [new UIMaterial(slot: "tag", replacement: $tag_name)]);
    }
}
