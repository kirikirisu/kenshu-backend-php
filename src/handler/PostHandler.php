<?php
require_once(dirname(__DIR__, 1) . "/client/PostClient.php");
require_once(dirname(__DIR__, 1) . "/model/dto/IndexPostDto.php");
require_once(dirname(__DIR__, 1) . "/model/dto/UpdatePostDto.php");
require_once(dirname(__DIR__, 1) . "/lib/PageComposer.php");
require_once (dirname(__DIR__, 1)) . "/lib/Errors/InputError.php";
require_once(dirname(__DIR__, 1) . "/client/ImageClient.php");

class StoredImageDto
{
    public function __construct(
        public array  $stored_img_uri_list,
        public string $thumbnail_uri)
    {
    }
}

class PostHandler
{
    public static function getPostListPage(PageComposer $compose): void
    {
        $post_client = new PostClient();
        $post_list = $post_client->getPostList();

        $compose->topPage($post_list)->renderHTML();
    }


    public static function storeImageBinaryToDisk(array $image_list, string $main_image): StoredImageDto
    {
        $img_public_dir = "/assets/images/";
        $saved_img_uri_list = [];
        $thumbnail_uri = '';

        foreach ($image_list['error'] as $key => $error) {
            if ($error == UPLOAD_ERR_OK) {
                $file_name = $_FILES['images']['name'][$key];

                $uniqu_file_name = sprintf('%s_%s.%s', pathinfo($file_name, PATHINFO_FILENAME), time(), pathinfo($file_name, PATHINFO_EXTENSION));
                $image_uri = sprintf('%s%s', $img_public_dir, $uniqu_file_name);
                $saved_img_uri_list[] = $image_uri;

                if ($file_name === $main_image) $thumbnail_uri = $image_uri;

                $temp_file_path = $_FILES['images']['tmp_name'][$key];
                $stored_dir = sprintf('%s%s', dirname(__DIR__) . "/public/assets/images/", $uniqu_file_name);
                move_uploaded_file($temp_file_path, $stored_dir);
            }
        }

        return new StoredImageDto(stored_img_uri_list: $saved_img_uri_list, thumbnail_uri: $thumbnail_uri);
    }

    public static function createPost(PageComposer $compose): void
    {
        $title = $_POST['post-title'];
        $body = $_POST['post-body'];
        $image_list = $_FILES['images'];
        $main_image = $_POST['main-image'];

        $error_list = static::validatePost(title: $title, body: $body, main_image: $main_image);
        if (count($error_list) > 0) static::renderTopPageWithError($compose, $error_list);

        $stored_img_binary = self::storeImageBinaryToDisk(image_list: $image_list, main_image: $main_image);

        $post_client = new PostClient();
        $payload = new IndexPostDto(user_id: 2, title: $title, body: $body, thumbnail_id: $stored_img_binary->thumbnail_uri);
        $post_id = $post_client->createPost($payload);

        $image_client = new ImageClient();
        $image_client->createMultiImageForPost(post_id: $post_id, image_list: $stored_img_binary->stored_img_uri_list);

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
