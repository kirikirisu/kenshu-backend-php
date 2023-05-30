<?php
require_once(dirname(__DIR__, 1) . "/model/Post.php");
require_once(dirname(__DIR__, 1) . "/lib/Errors/InputError.php");

class PageComposer
{
    public string $page = "";

    /**
     * @param Post[] $data_chunk
     * @param InputError[] $error_list
     */
    public function topPage(array $data_chunk, array $error_list = null): self
    {
        $top_page_base_html = file_get_contents(dirname(__DIR__) . '/view/html/page/top.html');
        $horizontal_card = file_get_contents(dirname(__DIR__) . '/view/html/part/horizontal-card.html');

        $post_list_fragment = "";

        foreach ($data_chunk as $post) {
            $injected_title = str_replace("%title%", htmlspecialchars($post->title), $horizontal_card);
            $post_list_fragment = $post_list_fragment . str_replace("%post_id%", $post->id, $injected_title);
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
            }
        }

        $this->page = str_replace("%invalid_title%", "", $this->page);
        $this->page = str_replace("%invalid_body%", "", $this->page);
        return $this;
    }

    public function postDetailPage(Post $post)
    {
        $post_detail_page_base_html = file_get_contents(dirname(__DIR__) . '/view/html/page/postDetail.html');
        $title_replaced= str_replace("%title%", $post->title, $post_detail_page_base_html);
        $this->page = str_replace("%body%", $post->body, $title_replaced);
    }

    public function renderHTML(): void
    {
        echo $this->page;
    }
}
