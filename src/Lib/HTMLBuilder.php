<?php

namespace App\Lib;

use App\Lib\Component\BadgeList;
use App\Lib\Component\CheckBox;
use App\Lib\Component\ComponentBuilder;
use App\Lib\Component\HorizontalCard;
use App\Lib\Component\Image;
use App\Lib\Error\InputError;
use App\Lib\Struct\QueryParam;
use App\Lib\Struct\UIMaterial;
use App\Lib\Validator\ErrorMessage;
use App\Model\Dto\Image\IndexImageDto;
use App\Model\Dto\Post\ShowPostDto;
use App\Model\Dto\Tag\IndexTagDto;
use App\Model\Dto\Tag\PostTagListDto;

class HTMLBuilder implements HTMLBuilderInterface
{
    public string $page = "";

    /**
     * @param ShowPostDto[] $post_list
     * @param array<string, PostTagListDto> $post_tag_hash_map
     * @param string $csrf_token
     * @param QueryParam[] $query
     * @param array|null $error_list
     * @return $this
     */
    public function topPage(array $post_list, array $post_tag_hash_map, array $query, string $csrf_token, array $error_list = null): self
    {
        $post_list_fragment = "";
        foreach ($post_list as $post) {
            $post_list_fragment = $post_list_fragment . HorizontalCard::render($post, $post_tag_hash_map);
        }

        $ui_material_list = [
            new UIMaterial(slot: "post_list", replacement: $post_list_fragment),
            new UIMaterial(slot: "post_title", replacement: $post_list_fragment),
            new UIMaterial(slot: "csrf", replacement: $csrf_token),
        ];

        foreach ($query as $q) {
            $error_ui_material = ErrorMessage::getErrorUIMaterial($q);
            if (is_null($error_ui_material)) continue;

            $ui_material_list[] = $error_ui_material;
        }

        $this->page = ComponentBuilder::defineComponent(content_path: 'page/top.html', props: $ui_material_list);

        return $this;
    }

    /**
     * @param ShowPostDto $post
     * @param IndexImageDto[] $image_list
     * @param IndexTagDto[] $tag_list
     * @return $this
     */
    public function postDetailPage(ShowPostDto $post, array $image_list, array $tag_list): self
    {

        $tag_name_list = [];
        foreach ($tag_list as $tag) {
            $tag_name_list[] = $tag->name;
        }

        $this->page = ComponentBuilder::defineComponent(
            content_path: 'page/post-detail.html',
            props: [
                new UIMaterial(slot: "title", replacement: htmlspecialchars($post->title)),
                new UIMaterial(slot: "body", replacement: htmlspecialchars($post->body)),
                new UIMaterial(slot: "tags", replacement: BadgeList::render(tag_name_list: $tag_name_list)),
                new UIMaterial(slot: "images", replacement: self::createImageList(image_list: $image_list, thumbnail_url: $post->thumbnail_url)),
                new UIMaterial(slot: "user-avatar", replacement: $post->user_avatar),
                new UIMaterial(slot: "user-name", replacement: $post->user_name),
            ]
        );

        return $this;
    }


    /**
     * @param ShowPostDto $post
     * @param string $csrf_token
     * @param IndexImageDto[] $image_list
     * @param IndexTagDto[] $tag_list
     * @param int[] $checked_tag_id_list
     * @param array|null $error_list
     * @return $this
     */
    public function postEditPage(ShowPostDto $post, string $csrf_token, array $image_list, array $tag_list, array $checked_tag_id_list, array $error_list = null): self
    {
        $tag_list_fragment = self::createCheckboxList(checkbox_list: $tag_list, checked_tag_id_list: $checked_tag_id_list);
        $image_list_fragment = self::createImageList(image_list: $image_list, thumbnail_url: $post->thumbnail_url);

        /* @var UIMaterial[] $ui_material_list */
        $ui_material_list = [
            new UIMaterial(slot: "title", replacement: htmlspecialchars($post->title)),
            new UIMaterial(slot: "body", replacement: htmlspecialchars($post->body)),
            new UIMaterial(slot: "tag_list", replacement: $tag_list_fragment),
            new UIMaterial(slot: "image_list", replacement: $image_list_fragment),
            new UIMaterial(slot: "csrf", replacement: $csrf_token),
        ];

        /* @var UIMaterial[] $error_ui_material_list */
        $error_ui_material_list = [];
        if ($error_list) {
            foreach ($error_list as $error) {
                if ($error->field === "title") {
                    $error_ui_material_list[] = new UIMaterial(slot: "invalid_title", replacement: '<p class="mt-1 text-pink-600">' . $error->title . '</p>');
                }
                if ($error->field === "body") {
                    $error_ui_material_list[] = new UIMaterial(slot: "invalid_body", replacement: '<p class="mt-1 text-pink-600">' . $error->title . '</p>');
                }
            }
        }

        $this->page = ComponentBuilder::defineComponent(content_path: 'page/post-edit.html', props: [...$ui_material_list, ...$error_ui_material_list]);

        return $this;
    }

    /**
     * @param string $csrf_token
     * @param InputError[]|null $error_list
     * @return $this
     */
    public function signUpPage(string $csrf_token, ?array $error_list = null): self
    {
        $error_props = [];

        if (!is_null($error_list)) {
            foreach ($error_list as $error) {
                if ($error->field) $error_props[] = new UIMaterial(slot: "invalid_" . $error->field, replacement: '<p class="mt-1 text-pink-600">' . $error->message . '</p>');
            }
        }


        $this->page = ComponentBuilder::defineComponent(content_path: 'page/user-signup.html', props: [new UIMaterial(slot: "csrf", replacement: $csrf_token), ...$error_props]);
        return $this;
    }

    public function signInPage(string $csrf_token): self
    {
        $this->page = ComponentBuilder::defineComponent(content_path: 'page/user-signin.html', props: [new UIMaterial(slot: "csrf", replacement: $csrf_token)]);

        return $this;
    }

    public function getHtml(): string
    {
        return $this->page;
    }

    /**
     * @param IndexTagDto[] $checkbox_list
     * @param int[] $checked_tag_id_list
     * @return string
     */
    public static function createCheckboxList(array $checkbox_list, array|null $checked_tag_id_list): string
    {
        $fragment = "";
        foreach ($checkbox_list as $checkbox) {
            $isChecked = in_array($checkbox->id, $checked_tag_id_list) && !is_null($checked_tag_id_list);

            $fragment = $fragment . CheckBox::render(id: $checkbox->id, name: $checkbox->name, isChecked: $isChecked);
        }

        return $fragment;
    }

    /**
     * @param IndexImageDto[] $image_list
     * @param string $thumbnail_url
     * @return string
     */
    public static function createImageList(array $image_list, string $thumbnail_url): string
    {

        $image_list_fragment = "";
        foreach ($image_list as $image) {
            $image_list_fragment = $image_list_fragment . Image::render(url: $image->url, isThumbnail: $thumbnail_url === $image->url);
        }

        return $image_list_fragment;
    }
}
