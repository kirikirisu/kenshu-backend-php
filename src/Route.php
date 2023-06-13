<?php
require_once(dirname(__DIR__) . "/src/client/PostClient.php");
require_once(dirname(__DIR__) . "/src/lib/Singleton/PageCompose.php");
require_once(dirname(__DIR__) . "/src/handler/GetTopPageHandler.php");
require_once(dirname(__DIR__) . "/src/handler/CreatePostHandler.php");
require_once(dirname(__DIR__) . "/src/handler/GetPostDetailPageHandler.php");
require_once(dirname(__DIR__) . "/src/handler/GetPostEditPageHandler.php");
require_once(dirname(__DIR__) . "/src/handler/UpdatePostHandler.php");
require_once(dirname(__DIR__) . "/src/handler/DeletePostHandler.php");
require_once(dirname(__DIR__) . "/src/lib/Http/Request.php");
require_once(dirname(__DIR__) . "/src/handler/NotFoundHandler.php");

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
