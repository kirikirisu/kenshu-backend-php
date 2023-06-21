<?php

namespace App\Handler;

use App\Lib\Error\InputError;
use App\Lib\HTMLBuilder;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Validator\ValidateImageFile;
use App\Lib\Validator\ValidatePost;
use App\Model\Dto\IndexPostDto;
use App\Repository\ImageRepository;
use App\Repository\PostCategoryRepository;
use App\Repository\PostRepository;
use App\Lib\Manager\CsrfManager;

const PUBLICK_DIR_FOR_IMG = "/assets/images/";

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
        public Request                $req,
        public \PDO                   $pdo,
        public HTMLBuilder            $compose,
        public PostRepository         $post_repo,
        public ImageRepository        $image_repo,
        public PostCategoryRepository $post_category_repo)
    {
    }

    public function run(): Response
    {
        $title = $this->req->post['post-title'];
        $body = $this->req->post['post-body'];
        $main_image = $this->req->post['main-image'];
        $image_list = $this->req->files['images'];
        $category_list = self::collectCategoryNumber($this->req->post['categories'] ?? []);

        if (!CsrfManager::validate($this->req->post['csrf'])) return new Response(status_code: OK_STATUS_CODE, html: "<div>エラーが発生しました。</div>");

        $error_list = ValidatePost::exec(title: $title, body: $body, main_image: $main_image);
        if (count($error_list) > 0) return static::createTopPageWithError(compose: $this->compose, post_repo: $this->post_repo, error_list: $error_list);

        $img_error = ValidateImageFile::exec(req: $this->req);
        // TODO: create ui
        if (!is_null($img_error)) return new Response(status_code: OK_STATUS_CODE, html: "<div>{$img_error->message}</div>");

        $stored_img_binary = self::storeImageBinaryToDisk(image_list: $image_list, main_image: $main_image);

        try {
            $this->pdo->beginTransaction();
            $payload = new IndexPostDto(user_id: 2, title: $title, body: $body);
            $post_id = $this->post_repo->insertPost($payload);
            $img_id = $this->image_repo->insertImage(post_id: $post_id, img_path: $stored_img_binary->thumbnail_uri);
            $this->post_repo->updateThumbnail(post_id: $post_id, thumbnail_id: $img_id);

            $this->post_category_repo->insertMultiCategory(post_id: $post_id, category_list: $category_list);
            $this->image_repo->insertMultiImageForPost(post_id: $post_id, image_list: $stored_img_binary->stored_img_uri_list);
            $this->pdo->commit();
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            return new Response(status_code: INTERNAL_SERVER_ERROR_STATUS_CODE, html: "<div>サーバーでエラーが発生しました。</div>");
        }

        return new Response(status_code: SEE_OTHER_STATUS_CODE, redirect_url: "http://localhost:8080");
    }

    /**
     * @param array $raw_categories
     * @return int[]
     */
    public static function collectCategoryNumber(array $raw_categories): array
    {
        if (count($raw_categories) <= 0) return [];
        $category_list = [];
        foreach ($raw_categories as $category) {
            $category_list[] = (int)$category;
        }
        return $category_list;
    }

    public static function storeImageBinaryToDisk(array $image_list, string $main_image): StoredImageDto
    {
        $saved_img_uri_list = [];
        $thumbnail_uri = '';

        foreach ($image_list['error'] as $key => $error) {
            if ($error == UPLOAD_ERR_OK) {
                $file_name = $_FILES['images']['name'][$key];

                $uniqu_file_name = sprintf('%s_%s.%s', pathinfo($file_name, PATHINFO_FILENAME), time(), pathinfo($file_name, PATHINFO_EXTENSION));
                $image_uri = sprintf('%s%s', PUBLICK_DIR_FOR_IMG, $uniqu_file_name);

                $temp_file_path = $_FILES['images']['tmp_name'][$key];
                $stored_dir = sprintf('%s%s', dirname(__DIR__) . "/public/assets/images/", $uniqu_file_name);
                move_uploaded_file($temp_file_path, $stored_dir);

                if ($file_name === $main_image) {
                    $thumbnail_uri = $image_uri;
                    continue;
                }

                $saved_img_uri_list[] = $image_uri;
            }
        }

        return new StoredImageDto(stored_img_uri_list: $saved_img_uri_list, thumbnail_uri: $thumbnail_uri);
    }

    /**
     * @param HTMLBuilder $compose
     * @param PostRepository $post_repo
     * @param InputError[] $error_list
     * @return Response
     */
    public static function createTopPageWithError(HTMLBuilder $compose, PostRepository $post_repo, array $error_list): Response
    {
        $post_list = $post_repo->getPostList();

        $html = $compose->topPage($post_list, $error_list)->getHtml();
        return new Response(status_code: OK_STATUS_CODE, html: $html);
    }
}
