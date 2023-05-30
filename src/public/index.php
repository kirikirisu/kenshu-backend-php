<?php
  require_once(dirname(__DIR__, 1)."/handler/PostHandler.php");

  $request_url = $_SERVER["REQUEST_URI"];
  $request_method = $_SERVER["REQUEST_METHOD"];

  if ($request_method === "GET" && $request_url === "/") {
    PostHandler::getPostListPage();
    
  } else if ($request_method === "POST" && $request_url === "/posts")  {
    PostHandler::createPost();

  } else if ($request_method === "GET" && $request_url === "/posts/:id"){
  }
