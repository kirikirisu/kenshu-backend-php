<?php

namespace App\Lib;

use App\Lib\Error\InputError;
use App\Model\Dto\ShowPostDto;

class HTMLBuilder
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
        $tag_part = file_get_contents(dirname(__DIR__) . '/view/html/part/tag.html');

        $tag_list_fragment = "";
        $post_list_fragment = "";

        foreach ($data_chunk as $post) {
            foreach($post->tag_list as $tag) {
                $tag_list_fragment = $tag_list_fragment . str_replace("%tag%", $tag, $tag_part);
            }
            $injected_title = str_replace("%title%", htmlspecialchars($post->title), $horizontal_card);
            $injected_post_id = str_replace("%post_id%", $post->id, $injected_title);
            $injected_body = str_replace("%body%", $post->body, $injected_post_id);
            $injected_image = str_replace("%image%", $post->thumbnail_id, $injected_body);
            $post_list_fragment = $post_list_fragment . str_replace("%tags%", $tag_list_fragment, $injected_image);
        }

        $this->page = str_replace("%post_list%", $post_list_fragment, $top_page_base_html);

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

    public function postDetailPage(ShowPostDto $post): self
    {
        $post_detail_page_base_html = file_get_contents(dirname(__DIR__) . '/view/html/page/postDetail.html');
        $title_replaced = str_replace("%title%", htmlspecialchars($post->title), $post_detail_page_base_html);
        $this->page = str_replace("%body%", htmlspecialchars($post->body), $title_replaced);

        return $this;
    }

    public function postEditPage(ShowPostDto $post, array $error_list = null): self
    {
        $edit_post_page_base_html = file_get_contents(dirname(__DIR__) . '/view/html/page/editPost.html');
        $title_replaced = str_replace("%title%", htmlspecialchars($post->title), $edit_post_page_base_html);
        $this->page = str_replace("%body%", htmlspecialchars($post->body), $title_replaced);

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
}
