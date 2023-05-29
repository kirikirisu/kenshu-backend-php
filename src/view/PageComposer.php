<?php
  require_once(dirname(__DIR__, 1)."/model/Post.php");
  require_once(dirname(__DIR__, 1)."/class/InputError.php");

  class PageComposer
  {
    public string $page = "";

    /**
    * @param Post[] $data_chunk
    * @param InputError[] $error_list
    */
    public function topPage($data_chunk, $error_list = null) {
      $top_page_base_html = file_get_contents(dirname(__DIR__).'/ui/page/top.html');
      $horizontal_card = file_get_contents(dirname(__DIR__).'/ui/part/horizontal-card.html');

      $post_list_fragment = "";

      foreach($data_chunk as $post) {
        $post_list_fragment = $post_list_fragment.str_replace("%title%", $post->title, $horizontal_card);
      }

      $this->page = str_replace("%post_list%", $post_list_fragment, $top_page_base_html);

      if ($error_list) {
        foreach($error_list as $error) {
          if ($error->field === "title") {
            $this->page = str_replace("%invalid_title%", '<p class="mt-1 text-pink-600">'.$error->message."</p>", $this->page);
          }
          if ($error->field === "body") {
            $this->page = str_replace("%invalid_body%", '<p class="mt-1 text-pink-600">'.$error->message."</p>", $this->page);
          }
        }
      }

      $this->page = str_replace("%invalid_title%", "", $this->page);
      $this->page = str_replace("%invalid_body%", "", $this->page);
    }

    public function postDetailPage() {}

    public function renderHTML() {
      echo $this->page;
    }
  }
