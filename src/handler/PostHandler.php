<?php
require_once(dirname(__DIR__, 1) . "/client/PostClient.php");
require_once(dirname(__DIR__, 1) . "/client/PostPayload.php");
require_once(dirname(__DIR__, 1) . "/view/PageComposer.php");
require_once (dirname(__DIR__, 1)) . "/lib/Errors/InputError.php";

class PostHandler
{
    public static function getPostListPage(): void
    {
        $post_client = new PostClient();
        $post_list = $post_client->getPostList();

        $compose = new PageComposer();
        $compose->topPage($post_list)->renderHTML();
    }

    public static function createPost(): void
    {
        $title = $_POST['post-title'];
        $body = $_POST['post-body'];

        $error_list = static::validatePost(title: $title, body: $body);

        $post_client = new PostClient();
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
