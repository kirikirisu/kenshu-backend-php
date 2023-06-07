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
        $image_list = $_FILES['images'];
        $main_image = $_POST['main-image'];

        $error_list = static::validatePost(title: $title, body: $body, main_image: $main_image);
        if (count($error_list) > 0) static::renderTopPageWithError($compose, $error_list);

        $thumbnail_image_id = "";
        $upload_dir = dirname(__DIR__) . "/public/assets/images/";
        foreach ($image_list['error'] as $key => $error) {
            if ($error == UPLOAD_ERR_OK) {
                $file_name = $_FILES['images']['name'][$key];
                $temp_file_path = $_FILES['images']['tmp_name'][$key];

                $uniqu_file_name = sprintf('%s_%s.%s', pathinfo($file_name, PATHINFO_FILENAME), time(), pathinfo($file_name, PATHINFO_EXTENSION));
                $target_file_path = sprintf('%s%s', $upload_dir, $uniqu_file_name );

                if ($file_name === $main_image) {
                    $thumbnail_image_id = $target_file_path;
                }
                move_uploaded_file($temp_file_path, $target_file_path);
            }
        }

        $post_client = new PostClient();
        $payload = new IndexPostDto(user_id: 2, title: $title, body: $body, thumbnail_id: $thumbnail_image_id);
//      post_id を返したい
        $post_client->createPost($payload);
//      imageClientを作ってimagesテーブルに投稿(post_id)と紐付けて画像を保存する

        header("Location: http://localhost:8080", true, 303);
    }

    public static function getPostDetailPage(string $post_id, PageComposer $compose)
    {
        $post_client = new PostClient();
        $post = $post_client->getPostById($post_id);

        $compose->postDetailPage($post)->renderHTML();
    }

    public static function getEditPage(string $post_id, PageComposer $compose)
    {
        $post_client = new PostClient();
        $post = $post_client->getPostById($post_id);

        $compose->getPostEditPage(post: $post)->renderHTML();
    }

    public static function updatePost(string $post_id, PageComposer $compose): void
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

    public static function validatePost(string $title, string $body, string $main_image): array
    {
        /** @var InputError[] $error_list */
        $error_list = [];

        if ($title === "") {
            $error_list[] = new InputError("タイトルを入力してください。", "title");
        }
        if ($body === "") {
            $error_list[] = new InputError("本文を入力してください。", "body");
        }
        if ($main_image === "") {
            $error_list[] = new InputError("メイン画像を選択してください", "image");
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
