<?php

namespace App\Lib;

use App\Lib\Helper\HTMLBuilderHelper\HTMLBuilderHelper;
use App\Lib\Helper\HTMLBuilderHelper\UIMaterial;
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
     * @param array|null $error_list
     * @return $this
     */
    public function topPage(array $post_list, array $post_tag_hash_map, string $csrf_token, array $error_list = null): self
    {
        $top_page_base_html = file_get_contents(dirname(__DIR__) . '/view/html/page/top.html');

        $post_list_fragment = "";
        foreach ($post_list as $post) {
            $post_list_fragment = $post_list_fragment . self::createHorizontalCard($post, $post_tag_hash_map);
        }

        $ui_material_list = [
            new UIMaterial(slot: "post_list", replacement: $post_list_fragment),
            new UIMaterial(slot: "post_title", replacement: $post_list_fragment),
            new UIMaterial(slot: "csrf", replacement: $csrf_token),
        ];

        if ($error_list) {
            foreach ($error_list as $error) {
                if ($error->field === "title") {
                    $ui_material_list[] = new UIMaterial(slot: "invalid_title", replacement: '<p class="mt-1 text-pink-600">' . $error->message . '</p>');
                }
                if ($error->field === "body") {
                    $ui_material_list[] = new UIMaterial(slot: "invalid_body", replacement: '<p class="mt-1 text-pink-600">' . $error->message . '</p>');
                }
                if ($error->field === "image") {
                    $ui_material_list[] = new UIMaterial(slot: "invalid_image", replacement: '<p class="mt-1 text-pink-600">' . $error->message . '</p>');
                }
            }
        }

        $ui_material_list[] = new UIMaterial(slot: "invalid_title", replacement: '');
        $ui_material_list[] = new UIMaterial(slot: "invalid_body", replacement: '');
        $ui_material_list[] = new UIMaterial(slot: "invalid_image", replacement: '');

        $this->page = HTMLBuilderHelper::mixUiMaterial(base: $top_page_base_html, ui_material_list: $ui_material_list);

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
        $post_detail_page_base_html = file_get_contents(dirname(__DIR__) . '/view/html/page/postDetail.html');
        $ui_material_list = [
            new UIMaterial(slot: "title", replacement: htmlspecialchars($post->title)),
            new UIMaterial(slot: "body", replacement: htmlspecialchars($post->body)),
            new UIMaterial(slot: "tags", replacement: self::createBadgeList(tag_list: $tag_list)),
            new UIMaterial(slot: "images", replacement: self::createImageList(image_list: $image_list, thumbnail_url: $post->thumbnail_url)),
            new UIMaterial(slot: "user-avatar", replacement: $post->user_avatar),
            new UIMaterial(slot: "user-name", replacement: $post->user_name),
        ];

        $this->page = HTMLBuilderHelper::mixUiMaterial(base: $post_detail_page_base_html, ui_material_list: $ui_material_list);

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
        $edit_post_page_base_html = file_get_contents(dirname(__DIR__) . '/view/html/page/editPost.html');
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
        } else {
            $error_ui_material_list[] = new UIMaterial(slot: "invalid_title", replacement: "");
            $error_ui_material_list[] = new UIMaterial(slot: "invalid_body", replacement: "");
        }


        $this->page = HTMLBuilderHelper::mixUiMaterial(base: $edit_post_page_base_html, ui_material_list: [...$ui_material_list, ...$error_ui_material_list]);

        return $this;
    }

    public function signUpPage(string $csrf_token): self
    {
        $user_signup_base = file_get_contents(dirname(__DIR__) . '/view/html/page/user-signup.html');
        $this->page = HTMLBuilderHelper::mixUiMaterial(base: $user_signup_base, ui_material_list: [new UIMaterial(slot: "csrf", replacement: $csrf_token)]);
        return $this;
    }

    public function signInPage(string $csrf_token): self
    {
        $user_signin_base = file_get_contents(dirname(__DIR__) . '/view/html/page/user-signin.html');
        $this->page = HTMLBuilderHelper::mixUiMaterial(base: $user_signin_base, ui_material_list: [new UIMaterial(slot: "csrf", replacement: $csrf_token)]);

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
        $checkbox_part = file_get_contents(dirname(__DIR__) . '/view/html/part/checkbox.html');

        $fragment = "";
        foreach ($checkbox_list as $checkbox) {
            $ui_material_list = [
                new UIMaterial(slot: "id", replacement: $checkbox->id),
                new UIMaterial(slot: "tag_name", replacement: $checkbox->name),
            ];

            if (in_array($checkbox->id, $checked_tag_id_list) && !is_null($checked_tag_id_list)) {
                $ui_material_list[] = new UIMaterial(slot: "checked", replacement: "checked");
            } else {
                $ui_material_list[] = new UIMaterial(slot: "checked", replacement: "");
            }

            $fragment = $fragment . HTMLBuilderHelper::mixUiMaterial(base: $checkbox_part, ui_material_list: $ui_material_list);
        }

        return $fragment;
    }


    /**
     * @param IndexTagDto[] $tag_list
     * @return string
     */
    public static function createBadgeList(array $tag_list): string
    {
        $tag_part = file_get_contents(dirname(__DIR__) . '/view/html/part/badge.html');

        $tag_list_fragment = "";
        foreach ($tag_list as $tag) {
            $tag_list_fragment = $tag_list_fragment . HTMLBuilderHelper::mixUiMaterial(base: $tag_part, ui_material_list: [new UIMaterial(slot: "tag", replacement: $tag->name)]);
        }

        return $tag_list_fragment;
    }

    /**
     * @param ShowPostDto $post
     * @param array<string, PostTagListDto> $post_tag_hash_map
     * @return string
     */
    public static function createHorizontalCard(ShowPostDto $post, array $post_tag_hash_map): string
    {
        $horizontal_card = file_get_contents(dirname(__DIR__) . '/view/html/part/horizontal-card.html');
        $tag_list = $post_tag_hash_map[$post->id]->tag_list;

        $ui_material_list = [
            new UIMaterial(slot: "title", replacement: htmlspecialchars($post->title)),
            new UIMaterial(slot: "post_id", replacement: htmlspecialchars($post->id)),
            new UIMaterial(slot: "body", replacement: $post->body),
            new UIMaterial(slot: "image", replacement: $post->thumbnail_url),
            new UIMaterial(slot: "tags", replacement: self::createBadgeListFromString($tag_list)),
            new UIMaterial(slot: "user-avatar", replacement: $post->user_avatar),
            new UIMaterial(slot: "user-name", replacement: $post->user_name),
        ];

        return HTMLBuilderHelper::mixUiMaterial(base: $horizontal_card, ui_material_list: $ui_material_list);
    }

    /**
     * @param string[] $tag_list
     * @return string
     */
    public static function createBadgeListFromString(array $tag_list): string
    {
        $tag_part = file_get_contents(dirname(__DIR__) . '/view/html/part/badge.html');

        $tag_list_fragment = "";
        foreach ($tag_list as $tag) {
            $tag_list_fragment = $tag_list_fragment . HTMLBuilderHelper::mixUiMaterial(base: $tag_part, ui_material_list: [new UIMaterial(slot: "tag", replacement: $tag)]);
        }

        return $tag_list_fragment;
    }

    /**
     * @param IndexImageDto[] $image_list
     * @param string $thumbnail_url
     * @return string
     */
    public static function createImageList(array $image_list, string $thumbnail_url): string
    {
        $image_part = file_get_contents(dirname(__DIR__) . '/view/html/part/image.html');
        $thumbnail_style = "border-8 border-orange-500";

        $image_list_fragment = "";
        foreach ($image_list as $image) {
            $ui_material_list = [];
            $ui_material_list[] = new UIMaterial(slot: "src", replacement: $image->url);

            if ($thumbnail_url === $image->url) {
                $ui_material_list[] = new UIMaterial(slot: "thumbnail_style", replacement: $thumbnail_style);
            } else {
                $ui_material_list[] = new UIMaterial(slot: "thumbnail_style", replacement: "");
            }

            $image_list_fragment = $image_list_fragment . HTMLBuilderHelper::mixUiMaterial(base: $image_part, ui_material_list: $ui_material_list);
        }

        return $image_list_fragment;
    }
}
