<?php
require_once(dirname(__DIR__, 1) . "/client/PostClient.php");
require_once(dirname(__DIR__, 1) . "/model/dto/IndexPostDto.php");
require_once(dirname(__DIR__, 1) . "/lib/PageComposer.php");
require_once (dirname(__DIR__, 1)) . "/lib/Errors/InputError.php";

class PostHandler
{
    public static function getPostListPage(PageComposer $compose): void
    {
        $post_client = new PostClient();
        $post_list = $post_client->getPostList();

        $compose->topPage($post_list)->renderHTML();
    }

    public static function createPost(PageComposer $compose): void
    {
        $title = $_POST['post-title'];
        $body = $_POST['post-body'];

        $error_list = static::validatePost(title: $title, body: $body);

        $post_client = new PostClient();
        if (count($error_list) > 0) {
            $post_list = $post_client->getPostList();

            $compose->topPage($post_list, $error_list)->renderHTML();
            exit;
        }

        $payload = new IndexPostDto(2, $title, $body, 1);
        $post_client->createPost($payload);

        header("Location: http://localhost:8080", true, 303);
    }

    public static function getPostDetailPage(string $post_id, PageComposer $compose)
    {
        $post_client = new PostClient();
        $post = $post_client->getPostById($post_id);

        $compose->postDetailPage($post)->renderHTML();
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
