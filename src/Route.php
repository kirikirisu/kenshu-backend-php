<?php
require_once(dirname(__DIR__) . "/src/client/PostClient.php");
require_once(dirname(__DIR__) . "/src/lib/Singleton/PageCompose.php");
require_once(dirname(__DIR__) . "/src/handler/GetTopPageHandler.php");
require_once(dirname(__DIR__) . "/src/handler/CreatePostHandler.php");
require_once(dirname(__DIR__) . "/src/handler/GetPostDetailPageHandler.php");
require_once(dirname(__DIR__) . "/src/handler/GetPostEditPageHandler.php");
require_once(dirname(__DIR__) . "/src/handler/UpdatePostHandler.php");
require_once(dirname(__DIR__) . "/src/handler/DeletePostHandler.php");
require_once(dirname(__DIR__) . "/src/handler/Handle404.php");

class Route
{
    public static function getHandler(string $request_method, string $request_url)
    {
        if ($request_method === "GET" && $request_url === "/") {
            return new GetTopPageHandler(compose: PageCompose::getComposer(), post_client: new PostClient());

        } else if ($request_method === "POST" && $request_url === "/") {
            return new CreatePosthandler(compose: PageCompose::getComposer(), post_client: new PostClient());

        } else if ($request_method === "GET" && preg_match("|\A/posts/([0-9]+)\z|u", $request_url, $match)) {
            $post_id = (int)$match[1];
            return new GetPostDetailPageHandler(post_id: $post_id, compose: PageCompose::getComposer(), post_client: new PostClient());

        } else if ($request_method === "GET" && preg_match("|\A/posts/([0-9]+)/edit\z|u", $request_url, $match)) {
            $post_id = (int)$match[1];
            return new GetPostEditPageHandler(post_id: $post_id, compose: PageCompose::getComposer(), post_client: new PostClient());

        } else if ($request_method === "PATCH" && preg_match("|\A/posts/([0-9]+)/edit\z|u", $request_url, $match)) {
            $post_id = (int)$match[1];
            return new UpdatePostHandler(post_id: $post_id, post_client: new PostClient());

        } else if ($request_method === "DELETE" && preg_match("|\A/posts/([0-9]+)/edit\z|u", $request_url, $match)) {
            $post_id = (int)$match[1];
            return new DeletePostHandler(post_id: $post_id, post_client: new PostClient());

        }
        return new Handle404();
    }
}
