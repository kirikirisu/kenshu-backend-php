<?php

namespace App\Handler;

use App\Lib\Errors\InputError;
use App\Lib\HTMLBuilder;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Validator\ValidatePost;
use App\Model\Dto\IndexPostDto;
use App\Repository\ImageRepository;
use App\Repository\PostRepository;

class StoredImageDto
{
    public function __construct(
        public array  $stored_img_uri_list,
        public string $thumbnail_uri)
    {
    }
}

class CreatePostHandler implements HandlerInterface
{
    public function __construct(
        public Request         $req,
        public HTMLBuilder     $compose,
        public PostRepository  $post_repo,
        public ImageRepository $image_repo)
    {
    }

    public function run(): Response
    {
        $title = $this->req->post['post-title'];
        $body = $this->req->post['post-body'];
        $image_list = $this->req->files['images'];
        $main_image = $_POST['main-image'];

        $error_list = ValidatePost::exec(title: $title, body: $body, main_image: $main_image);
        if (count($error_list) > 0) return static::createTopPageWithError(compose: $this->compose, post_repo: $this->post_repo, error_list: $error_list);

        $stored_img_binary = self::storeImageBinaryToDisk(image_list: $image_list, main_image: $main_image);
        $payload = new IndexPostDto(user_id: 2, title: $title, body: $body, thumbnail_id: $stored_img_binary->thumbnail_uri);
        $post_id = $this->post_repo->createPost($payload);
        $this->image_repo->createMultiImageForPost(post_id: $post_id, image_list: $stored_img_binary->stored_img_uri_list);

        return new Response(status_code: SEE_OTHER_STATUS_CODE, redirect_url: "http://localhost:8080");
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

    /**
     * @param HTMLBuilder $compose
     * @param InputError[] $error_list
     */
    public static function createTopPageWithError(HTMLBuilder $compose, PostRepository $post_repo, array $error_list): Response
    {
        $post_list = $post_repo->getPostList();

        $html = $compose->topPage($post_list, $error_list)->getHtml();
        return new Response(status_code: OK_STATUS_CODE, html: $html);
    }
}
