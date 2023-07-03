<?php

namespace App\Lib\Component;

use App\Lib\Struct\UIMaterial;

class BadgeList
{
    public static function render(array $tag_name_list): string
    {
        $fragment = "";

        foreach($tag_name_list as $tag_name) {
            $fragment = $fragment . ComponentBuilder::defineComponent(content_path: 'part/badge.html', props: [new UIMaterial(slot: "tag", replacement: $tag_name)]);
        }

        return $fragment;
    }
}
