<?php
require_once(dirname(__DIR__, 1) . "/client/PostClient.php");
require_once(dirname(__DIR__, 1) . "/lib/PageComposer.php");
require_once(dirname(__DIR__, 1). "/lib/Http/Request.php");

class CreatePostHandler
{
    public function __construct(
        public PageComposer $compose,
        public PostClient $post_client)
    {
    }

    public function run()
    {
        $title = $_POST['post-title'];
        $body = $_POST['post-body'];

        $error_list = static::validatePost(title: $title, body: $body);
        if (count($error_list) > 0) static::renderTopPageWithError($this->compose, $error_list);

        $payload = new IndexPostDto(2, $title, $body, 1);
        $this->post_client->createPost($payload);

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

    /**
     * @param PageComposer $compose
     * @param InputError[] $error_list
     */
    public static function renderTopPageWithError(PageComposer $compose, array $error_list)
    {
        $post_client = new PostClient();
        $post_list = $post_client->getPostList();

        $compose->topPage($post_list, $error_list)->renderHTML();
        exit;
    }
}
