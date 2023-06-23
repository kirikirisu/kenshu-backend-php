<?php

namespace App\Lib;

use App\Lib\Error\InputError;
use App\Lib\Manager\CsrfManager;
use App\Model\Dto\DetailPostDto;
use App\Model\Dto\IndexImageDto;
use App\Model\Dto\IndexTagDto;
use App\Model\Dto\ShowPostDto;

class HTMLBuilder implements HTMLBuilderInterface
{
    public string $page = "";

    /**
     * @param ShowPostDto[] $data_chunk
     * @param InputError[] $error_list
     */
    public function topPage(array $data_chunk, array $error_list = null): self
    {
        $top_page_base_html = file_get_contents(dirname(__DIR__) . '/view/html/page/top.html');
        $horizontal_card = file_get_contents(dirname(__DIR__) . '/view/html/part/horizontal-card.html');

        $post_list_fragment = "";

        foreach ($data_chunk as $post) {
            $tag_list_fragment = self::createBadgeListFromString($post->tag_list);
            $injected_title = str_replace("%title%", htmlspecialchars($post->title), $horizontal_card);
            $injected_post_id = str_replace("%post_id%", $post->id, $injected_title);
            $injected_body = str_replace("%body%", $post->body, $injected_post_id);
            $injected_image = str_replace("%image%", $post->thumbnail_url, $injected_body);
            $post_list_fragment = $post_list_fragment . str_replace("%tags%", $tag_list_fragment, $injected_image);
        }

        $replaced_post_list = str_replace("%post_list%", $post_list_fragment, $top_page_base_html);
        $this->page = str_replace("%csrf%", CsrfManager::generate(), $replaced_post_list);

        if ($error_list) {
            foreach ($error_list as $error) {
                if ($error->field === "title") {
                    $this->page = str_replace("%invalid_title%", '<p class="mt-1 text-pink-600">' . $error->message . "</p>", $this->page);
                }
                if ($error->field === "body") {
                    $this->page = str_replace("%invalid_body%", '<p class="mt-1 text-pink-600">' . $error->message . "</p>", $this->page);
                }
                if ($error->field === "image") {
                    $this->page = str_replace("%invalid_image%", '<p class="mt-1 text-pink-600">' . $error->message . "</p>", $this->page);
                }
            }
        }

        $this->page = str_replace("%invalid_title%", "", $this->page);
        $this->page = str_replace("%invalid_body%", "", $this->page);
        $this->page = str_replace("%invalid_image%", "", $this->page);
        return $this;
    }

    /**
     * @param DetailPostDto $post
     * @param IndexImageDto[] $image_list
     * @param IndexTagDto[] $tag_list
     * @return $this
     */
    public function postDetailPage(DetailPostDto $post, array $image_list, array $tag_list): self
    {
        $post_detail_page_base_html = file_get_contents(dirname(__DIR__) . '/view/html/page/postDetail.html');
        $title_replaced = str_replace("%title%", htmlspecialchars($post->title), $post_detail_page_base_html);
        $body_replaced = str_replace("%body%", htmlspecialchars($post->body), $title_replaced);
        $tag_replaced = str_replace("%tags%", self::createBadgeList(tag_list: $tag_list), $body_replaced);
        $this->page = str_replace("%images%", self::createImageList(image_list: $image_list, thumbnail_url: $post->thumbnail_url), $tag_replaced);

        return $this;
    }

    /**
     * @param DetailPostDto $post
     * @param IndexImageDto[] $image_list
     * @param IndexTagDto[] $tag_list
     * @param int[] $checked_tag_id_list
     * @param array|null $error_list
     * @return $this
     */
    public function postEditPage(DetailPostDto $post, array $image_list, array $tag_list, array $checked_tag_id_list, array $error_list = null): self
    {
        $edit_post_page_base_html = file_get_contents(dirname(__DIR__) . '/view/html/page/editPost.html');
        $title_replaced = str_replace("%title%", htmlspecialchars($post->title), $edit_post_page_base_html);
        $body_replaced = str_replace("%body%", htmlspecialchars($post->body), $title_replaced);
        $tag_list_fragment = self::createCheckboxList(checkbox_list: $tag_list, checked_tag_id_list: $checked_tag_id_list);
        $tag_list_replaced = str_replace("%tag_list%", $tag_list_fragment, $body_replaced);
        $image_list_fragment = self::createImageList(image_list: $image_list, thumbnail_url: $post->thumbnail_url);
        $image_list_replaced = str_replace("%image_list%", $image_list_fragment, $tag_list_replaced);
        $this->page = str_replace("%csrf%", CsrfManager::generate(), $image_list_replaced);

        if ($error_list) {
            foreach ($error_list as $error) {
                if ($error->field === "title") {
                    $this->page = str_replace("%invalid_title%", '<p class="mt-1 text-pink-600">' . $error->message . "</p>", $this->page);
                }
                if ($error->field === "body") {
                    $this->page = str_replace("%invalid_body%", '<p class="mt-1 text-pink-600">' . $error->message . "</p>", $this->page);
                }
            }
        }

        $this->page = str_replace("%invalid_title%", "", $this->page);
        $this->page = str_replace("%invalid_body%", "", $this->page);
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
            $id_replaced = str_replace(search: "%id%", replace: $checkbox->id, subject: $checkbox_part);
            $name_replaced = str_replace(search: "%tag_name%", replace: $checkbox->name, subject: $id_replaced);

            if (in_array($checkbox->id, $checked_tag_id_list) && !is_null($checked_tag_id_list)) {
               $fragment = $fragment . str_replace(search: "%checked%", replace: "checked", subject: $name_replaced);
            } else {
                $fragment = $fragment . str_replace(search: "%checked%", replace: "", subject: $name_replaced);
            }
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
            $tag_list_fragment = $tag_list_fragment . str_replace("%tag%", $tag->name, $tag_part);
        }

        return $tag_list_fragment;
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
            $tag_list_fragment = $tag_list_fragment . str_replace("%tag%", $tag, $tag_part);
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
            $replaced_image_list = str_replace("%src%", $image->url, $image_part);

            if ($thumbnail_url === $image->url) {
                $image_list_fragment = $image_list_fragment . str_replace("%thumbnail_style%", $thumbnail_style, $replaced_image_list);
            } else {
                $image_list_fragment = $image_list_fragment . str_replace("%thumbnail_style%", "", $replaced_image_list);
            }
        }

        return $image_list_fragment;
    }

}
