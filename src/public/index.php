<?php
require_once(dirname(__DIR__, 1) . "/handler/PostHandler.php");
require_once(dirname(__DIR__, 1) . "/lib/Singleton/PageCompose.php");

$request_url = $_SERVER["REQUEST_URI"];
$request_method = $_SERVER["REQUEST_METHOD"];

if ($request_method === "GET" && $request_url === "/") {
    PostHandler::getPostListPage(PageCompose::getComposer());

} else if ($request_method === "POST" && $request_url === "/") {
    PostHandler::createPost(PageCompose::getComposer());

} else if ($request_method === "GET" && preg_match("|\A/posts/([0-9]+)\z|u", $request_url, $match)) {
    $post_id = $match[1];
    PostHandler::getPostDetailPage(post_id: $post_id);
}
