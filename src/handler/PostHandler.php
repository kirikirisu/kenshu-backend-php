<?php
  require_once(dirname(__DIR__, 1)."/client/PostClient.php");
  require_once(dirname(__DIR__, 1)."/client/PostPayload.php");
  require_once(dirname(__DIR__, 1)."/view/PageComposer.php");
  require_once(dirname(__DIR__, 1)."/view/InputError.php");

  class PostHandler 
  {
    public static function getPostListPage() {
      $post_client = new PostClient();
      $post_list = $post_client->getPostList();

      $compose = new PageComposer();
      $compose->topPage($post_list);
      $compose->renderHTML();
    }

    public static function createPost() {
      $title = htmlspecialchars($_POST['post-title']);
      $body = htmlspecialchars($_POST['post-body']);
      
      $post_client = new PostClient();

      $raw_post = new ValidatePost(title: $title, body: $body);
      $error_list = $raw_post->validate();

      $post_client = new PostClient(pdo: PgConnect::getClient());
      if (count($error_list) > 0) {
        $post_list = $post_client->getPostList();

        $compose = new PageComposer();
        $compose->topPage($post_list, $error_list);
        $compose->renderHTML();
        exit;
      }
      
      $payload = new PostPayload(2, $title, $body, 1);
      $post_client->createPost($payload);

      header("Location: http://localhost:8080", true, 303);
      exit;
    }


  }

class ValidatePost {
  public string $title;
  public string $body;
  /** @var InputError[] $error_list */
  public $error_list = [];

  public function __construct(string $title, string $body)
  {
    $this->title = $title;
    $this->body = $body;
  }

  /** @return InputError[] */
  public function validate() {
    if ($this->title === "") {
      $this->error_list[] = new InputError("タイトルを入力してください。", "title"); 
    }
    if ($this->body === "") {
      $this->error_list[] = new InputError("本文を入力してください。", "body");
    }

    return $this->error_list;
  }

}
