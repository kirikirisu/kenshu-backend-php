<?php
namespace App\Lib\Component;

use App\Lib\Struct\UIMaterial;

class CheckBox
{
    public static function render(int $id, string $name, bool $isChecked): string
    {
        $ui_material_list = [
            new UIMaterial(slot: "id", replacement: $id),
            new UIMaterial(slot: "tag_name", replacement: $name),
        ];

        if ($isChecked) $ui_material_list[] = new UIMaterial(slot: "checked", replacement: "checked");

        return ComponentBuilder::defineComponent(content_path: 'part/checkbox.html', props: $ui_material_list);
    }
}
