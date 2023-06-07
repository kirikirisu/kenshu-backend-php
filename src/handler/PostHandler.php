<?php
require_once(dirname(__DIR__, 1) . "/client/PostClient.php");
require_once(dirname(__DIR__, 1) . "/model/dto/IndexPostDto.php");
require_once(dirname(__DIR__, 1) . "/model/dto/UpdatePostDto.php");
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
        if (count($error_list) > 0) static::renderTopPageWithError($compose, $error_list);

        $post_client = new PostClient();
        $payload = new IndexPostDto(2, $title, $body, 1);
        $post_client->createPost($payload);

        header("Location: http://localhost:8080", true, 303);
    }

    public static function getPostDetailPage(int $post_id, PageComposer $compose)
    {
        $post_client = new PostClient();
        $post = $post_client->getPostById($post_id);

        $compose->postDetailPage($post)->renderHTML();
    }

    public static function getEditPage(int $post_id, PageComposer $compose)
    {
        $post_client = new PostClient();
        $post = $post_client->getPostById($post_id);

        $compose->getPostEditPage(post: $post)->renderHTML();
    }

    public static function updatePost(int $post_id, PageComposer $compose): void
    {
        $body = file_get_contents('php://input');
        $data = json_decode($body);

        $error_list = static::validatePost(title: $data->title, body: $data->body);
        if (count($error_list) > 0) {
            header('Content-Type: application/json', true, 400);
            echo json_encode(array('message' => 'Patch request faild', 'errorMessage' => $error_list));
            exit;
        }

        $post_client = new PostClient();
        $dto = new UpdatePostDto(title: $data->title, body: $data->body, thumbnail_id: 1);
        $post_client->updatePost($post_id, $dto);

        $redirect_url = "http://localhost:8080/posts/" . $post_id;
        header('Content-Type: application/json', true, 201);
        echo json_encode(array('message' => 'Patch request succeeded', 'redirectUrl' => $redirect_url));
    }

    public static function deletePost(string $post_id): void
    {
        $post_client = new PostClient();
        $post_client->deletePost($post_id);

//        header("Location:  http://localhost:8080", true,303);
        $redirect_url = "http://localhost:8080/";
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Delete request succeeded', 'redirectUrl' => $redirect_url));
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
