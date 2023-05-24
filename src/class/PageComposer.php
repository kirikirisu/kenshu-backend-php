<?php
  class PageComposer
  {
    public string $page = "";

    public function topPage($data_chunk) {
      $html_file_path = dirname(__DIR__).'/page/top.html';
      $top_page_html = file_get_contents($html_file_path);
      $post_list_fragment = "";

      foreach($data_chunk as $post) {
        $post_list_fragment = $post_list_fragment."<li>".$post["title"]."</li>\n";
      }

      $this->page = str_replace("%post_list%", $post_list_fragment, $top_page_html);
    }

    public function renderHTML() {
      echo $this->page;
    }
  }
