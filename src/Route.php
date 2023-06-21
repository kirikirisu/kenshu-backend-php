<?php

namespace App;

use App\Handler\CreatePostHandler;
use App\Handler\DeletePostHandler;
use App\Handler\GetPostDetailPageHandler;
use App\Handler\GetPostEditPageHandler;
use App\Handler\GetTopPageHandler;
use App\Handler\HandlerInterface;
use App\Handler\NotFoundHandler;
use App\Handler\UpdatePostHandler;
use App\Lib\Http\Request;
use App\Lib\Singleton\PageCompose;
use App\Repository\ImageRepository;
use App\Repository\PostCategoryRepository;
use App\Repository\PostRepository;
use App\Lib\Singleton\PgConnect;

class Route
{
    public static function getHandler(Request $req): HandlerInterface
    {
        if ($req->method === "GET" && $req->path === "/") {
            return new GetTopPageHandler(compose: PageCompose::getComposer(), post_client: new PostRepository());

        } else if ($req->method === "POST" && $req->path === "/") {
            $pdo = PgConnect::getClient();
            return new CreatePosthandler(req: $req, pdo: $pdo, compose: PageCompose::getComposer(), post_repo: new PostRepository(pdo: $pdo), image_repo: new ImageRepository(pdo: $pdo), post_category_repo: new PostCategoryRepository(pdo: $pdo));

        } else if ($req->method === "GET" && preg_match("|\A/posts/([0-9]+)\z|u", $req->path, $match)) {
            $post_id = (int)$match[1];
            return new GetPostDetailPageHandler(post_id: $post_id, compose: PageCompose::getComposer(), post_client: new PostRepository());

        } else if ($req->method === "GET" && preg_match("|\A/posts/([0-9]+)/edit\z|u", $req->path, $match)) {
            $post_id = (int)$match[1];
            return new GetPostEditPageHandler(post_id: $post_id, compose: PageCompose::getComposer(), post_client: new PostRepository());

        } else if ($req->method === "PATCH" && preg_match("|\A/posts/([0-9]+)/edit\z|u", $req->path, $match)) {
            $post_id = (int)$match[1];
            return new UpdatePostHandler(req: $req, post_id: $post_id, compose: PageCompose::getComposer(), post_client: new PostRepository());

        } else if ($req->method === "DELETE" && preg_match("|\A/posts/([0-9]+)/edit\z|u", $req->path, $match)) {
            $post_id = (int)$match[1];
            return new DeletePostHandler(post_id: $post_id, post_client: new PostRepository());

        }
        return new NotFoundHandler();
    }
}
