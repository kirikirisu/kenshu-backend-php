<?php

namespace App;

use App\Client\PostClient;
use App\Handler\CreatePostHandler;
use App\Handler\DeletePostHandler;
use App\Handler\GetPostDetailPageHandler;
use App\Handler\GetPostEditPageHandler;
use App\Handler\GetTopPageHandler;
use App\Handler\NotFoundHandler;
use App\Handler\UpdatePostHandler;
use App\Lib\Http\Request;
use App\Lib\Singleton\PageCompose;


class Route
{
    public static function getHandler(Request $req)
    {
        if ($req->method === "GET" && $req->path === "/") {
            return new GetTopPageHandler(compose: PageCompose::getComposer(), post_client: new PostClient());

        } else if ($req->method === "POST" && $req->path === "/") {
            return new CreatePosthandler(req: $req, compose: PageCompose::getComposer(), post_client: new PostClient());

        } else if ($req->method === "GET" && preg_match("|\A/posts/([0-9]+)\z|u", $req->path, $match)) {
            $post_id = (int)$match[1];
            return new GetPostDetailPageHandler(post_id: $post_id, compose: PageCompose::getComposer(), post_client: new PostClient());

        } else if ($req->method === "GET" && preg_match("|\A/posts/([0-9]+)/edit\z|u", $req->path, $match)) {
            $post_id = (int)$match[1];
            return new GetPostEditPageHandler(post_id: $post_id, compose: PageCompose::getComposer(), post_client: new PostClient());

        } else if ($req->method === "PATCH" && preg_match("|\A/posts/([0-9]+)/edit\z|u", $req->path, $match)) {
            $post_id = (int)$match[1];
            return new UpdatePostHandler(req: $req, post_id: $post_id, compose: PageCompose::getComposer(), post_client: new PostClient());

        } else if ($req->method === "DELETE" && preg_match("|\A/posts/([0-9]+)/edit\z|u", $req->path, $match)) {
            $post_id = (int)$match[1];
            return new DeletePostHandler(post_id: $post_id, post_client: new PostClient());

        }
        return new NotFoundHandler();
    }
}
