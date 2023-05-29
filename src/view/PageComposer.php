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
<<<<<<< HEAD:src/view/PageComposer.php
    public function topPage($data_chunk) {
      $html_file_path = dirname(__DIR__).'/view/html/top.html';
=======
    public function topPage($data_chunk, $error_list = null) {
      $html_file_path = dirname(__DIR__).'/page/top.html';
>>>>>>> 968dfe0 (Validate form input):src/class/PageComposer.php
      $top_page_html = file_get_contents($html_file_path);
      $post_list_fragment = "";

      foreach($data_chunk as $post) {
        $post_list_fragment = $post_list_fragment."<li>".$post->title."</li>\n";
      }

      $this->page = str_replace("%post_list%", $post_list_fragment, $top_page_html);

      if ($error_list) {
        foreach($error_list as $error) {
          if ($error->field === "title") {
            $this->page = str_replace("%invalid_title%", "<p>".$error->message."</p>", $this->page);
          }
          if ($error->field === "body") {
            $this->page = str_replace("%invalid_body%", "<p>".$error->message."</p>", $this->page);
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
