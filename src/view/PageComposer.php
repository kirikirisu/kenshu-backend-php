<?php
  require_once(dirname(__DIR__, 1)."/model/Post.php");

  class PageComposer
  {
    public string $page = "";

    /**
    * @param Post[] $data_chunk
    */
    public function topPage(array $data_chunk): void {
      $html_file_path = dirname(__DIR__).'/view/html/top.html';
      $top_page_html = file_get_contents($html_file_path);
      $post_list_fragment = "";

      foreach($data_chunk as $post) {
        $post_list_fragment = $post_list_fragment."<li>".htmlspecialchars($post->title)."</li>\n";
      }

      $this->page = str_replace("%post_list%", $post_list_fragment, $top_page_html);
    }

    public function postDetailPage() {}

    public function renderHTML(): void {
      echo $this->page;
    }
  }
