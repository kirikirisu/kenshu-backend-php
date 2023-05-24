<?php
  require_once(dirname(__DIR__, 1)."/class/PgConnect.php");
  require_once(dirname(__DIR__, 1)."/class/PostClient.php");
  require_once(dirname(__DIR__, 1)."/class/PostPayload.php");

  class PostHandler 
  {
    public static function getPostListPage() {
      $html_file_path = dirname(__DIR__).'/page/top.html';
      $top_page_html = file_get_contents($html_file_path);

      $pg_client = PgConnect::getClient();
      $post_client = new PostClient(pdo: $pg_client);
      $post_list = $post_client->getPostList();
      $post_list_fragment = "";

      foreach($post_list as $post) {
        $post_list_fragment = $post_list_fragment."<li>".$post["title"]."</li>\n";
      }

      echo str_replace("%post_list%", $post_list_fragment, $top_page_html);
    }

    public static function createPost() {
      $title = htmlspecialchars($_POST['post-title']);
      $body = htmlspecialchars($_POST['post-body']);
      
      $pg_client = PgConnect::getClient();
      $post_client = new PostClient(pdo: $pg_client);
      $payload = new PostPayload(2, $title, $body, 1);
      $post_client->createPost($payload);

      header("Location: http://localhost:8080", true, 303);
      exit;
    }

  }
