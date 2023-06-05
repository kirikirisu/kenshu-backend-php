<?php
require_once(dirname(__DIR__, 1) . "/handler/PostHandler.php");
require_once(dirname(__DIR__, 1) . "/lib/Singleton/PageCompose.php");

$request_url = $_SERVER["REQUEST_URI"];
$request_method = $_SERVER["REQUEST_METHOD"];

if ($request_method === "GET" && $request_url === "/") {
    PostHandler::getPostListPage(compose: PageCompose::getComposer());

} else if ($request_method === "POST" && $request_url === "/") {
    PostHandler::createPost(compose: PageCompose::getComposer());

} else if ($request_method === "GET" && preg_match("|\A/posts/([0-9]+)\z|u", $request_url, $match)) {
    $post_id = $match[1];
    PostHandler::getPostDetailPage(post_id: $post_id, compose: PageCompose::getComposer());

} else if ($request_method === "GET" && preg_match("|\A/posts/([0-9]+)/edit\z|u", $request_url, $match)) {
    $post_id = $match[1];
    PostHandler::getEditPage(post_id: $post_id, compose: PageCompose::getComposer());

} else if ($request_method === "PATCH" && preg_match("|\A/posts/([0-9]+)/edit\z|u", $request_url, $match)) {
    $post_id = $match[1];

} else if ($request_method === "DELETE" && preg_match("|\A/posts/([0-9]+)/edit\z|u", $request_url, $match)) {
    $post_id = $match[1];

//    header("Location:  http://localhost:8080", true,303);
    $redirect_url = "http://localhost:8080/posts/" . $post_id;
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Delete request succeeded', 'redirectUrl' => $redirect_url));
    exit;

} else {
    echo "<h1>Page Not Found.</h1>" . $request_url.$request_method;
}
