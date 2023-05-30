<?php
  require_once(dirname(__DIR__, 1)."/client/PostClient.php");
  require_once(dirname(__DIR__, 1)."/client/PostPayload.php");
  require_once(dirname(__DIR__, 1)."/view/PageComposer.php");

  class PostHandler 
  {
    public static function getPostListPage(): void {
      $post_client = new PostClient();
      $post_list = $post_client->getPostList();

      $compose = new PageComposer();
      $compose->topPage($post_list);
      $compose->renderHTML();
    }

    public static function createPost(): void {

      $post_client = new PostClient();
      $payload = new PostPayload(user_id: 2, title: $_POST['post-title'], body: $_POST['post-body'], thumbnail_id: 1);
      $post_client->createPost($payload);

      header("Location: http://localhost:8080", true, 303);
    }

  }
