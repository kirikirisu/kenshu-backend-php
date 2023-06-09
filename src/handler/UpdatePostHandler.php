<?php
require_once(dirname(__DIR__) . "/lib/Http/Response.php");

class UpdatePostHandler
{

    public function __construct(
        public int          $post_id,
        public PostClient   $post_client)
    {
    }

    public function run(): Response
    {
        $title = $_POST['post-title'];
        $body = $_POST['post-body'];

//        $error_list = static::validatePost(title: $title, body: $body);
//        if (count($error_list) > 0) {
//            header('Content-Type: application/json', true, 400);
//            echo json_encode(array('message' => 'Patch request faild', 'errorMessage' => $error_list));
//            exit;
//        }

        $dto = new UpdatePostDto(title: $title, body: $body, thumbnail_id: 1);
        $this->post_client->updatePost($this->post_id, $dto);

        $redirect_url = "http://localhost:8080/posts/" . $this->post_id;
        return new Response(status_code: 303, redirect_url: $redirect_url);
    }

    public static function validatePost(string $title, string $body): array
    {
        /** @var InputError[] $error_list */
        $error_list = [];

        if ($title === "") {
            $error_list[] = new InputError("タイトルを入力してください。", "title");
        }
        if ($body === "") {
            $error_list[] = new InputError("本文を入力してください。", "body");
        }

        return $error_list;
    }
}
