<?php
namespace App\Lib\Component;

use App\Lib\Struct\UIMaterial;
use App\Model\Dto\Post\ShowPostDto;
use App\Model\Dto\Tag\PostTagListDto;

class HorizontalCard
{
    /**
     * @param ShowPostDto $post
     * @param array<string, PostTagListDto> $post_tag_hash_map
     * @return string
     */
    public static function render(ShowPostDto $post, array $post_tag_hash_map): string
    {
        $tag_list = $post_tag_hash_map[$post->id]->tag_list;

        $tag_list_fragment = "";
        foreach ($tag_list as $tag) {
            $tag_list_fragment = $tag_list_fragment . Badge::render(tag_name: $tag);
        }

        $ui_material_list = [
            new UIMaterial(slot: "title", replacement: htmlspecialchars($post->title)),
            new UIMaterial(slot: "post_id", replacement: htmlspecialchars($post->id)),
            new UIMaterial(slot: "body", replacement: $post->body),
            new UIMaterial(slot: "image", replacement: $post->thumbnail_url),
            new UIMaterial(slot: "tags", replacement: $tag_list_fragment),
            new UIMaterial(slot: "user-avatar", replacement: $post->user_avatar),
            new UIMaterial(slot: "user-name", replacement: $post->user_name),
        ];

        return ComponentBuilder::defineComponent(content_path: 'part/horizontal-card.html', props: $ui_material_list);
    }
}
