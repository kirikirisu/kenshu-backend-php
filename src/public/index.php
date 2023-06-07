<?php
//require_once(dirname(__DIR__, 1) . "/handler/PostHandler.php");
//require_once(dirname(__DIR__, 1) . "/lib/Singleton/PageCompose.php");
//require_once(dirname(__DIR__, 1) . "/client/PostClient.php");

require_once(dirname(__DIR__, 1) . "/App.php");

$app = new App();
$app->run();


//$request_url = $_SERVER["REQUEST_URI"];
//$request_method = $_SERVER["REQUEST_METHOD"];

//if ($request_method === "GET" && $request_url === "/") {
//    PostHandler::getPostListPage(compose: PageCompose::getComposer(), post_client: new PostClient());
//
//} else if ($request_method === "POST" && $request_url === "/") {
//    PostHandler::createPost(compose: PageCompose::getComposer(), post_client: new PostClient());
//
//} else if ($request_method === "GET" && preg_match("|\A/posts/([0-9]+)\z|u", $request_url, $match)) {
//    $post_id = (int)$match[1];
//    PostHandler::getPostDetailPage(post_id: $post_id, compose: PageCompose::getComposer(), post_client: new PostClient());
//
//} else if ($request_method === "GET" && preg_match("|\A/posts/([0-9]+)/edit\z|u", $request_url, $match)) {
//    $post_id = (int)$match[1];
//    PostHandler::getEditPage(post_id: $post_id, compose: PageCompose::getComposer(), post_client: new PostClient());
//
//} else if ($request_method === "PATCH" && preg_match("|\A/posts/([0-9]+)/edit\z|u", $request_url, $match)) {
//    $post_id = (int)$match[1];
//    PostHandler::updatePost(post_id: $post_id, post_client: new PostClient());
//
//} else if ($request_method === "DELETE" && preg_match("|\A/posts/([0-9]+)/edit\z|u", $request_url, $match)) {
//    $post_id = (int)$match[1];
//    PostHandler::deletePost(post_id: $post_id, post_client: new PostClient());
//
//} else {
//    echo "<h1>Page Not Found.</h1>\n" . $request_url . $request_method;
//}
