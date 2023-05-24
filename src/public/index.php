<?php
  require_once(dirname(__DIR__, 1)."/class/PgConnect.php");
  require_once(dirname(__DIR__, 1)."/class/PostClient.php");
  require_once(dirname(__DIR__, 1)."/class/PostPayload.php");

  $request_url = $_SERVER["REQUEST_URI"];
  $request_method = $_SERVER["REQUEST_METHOD"];

  if ($request_method === "GET" && $request_url === "/") {
    $html_file_path = dirname(__DIR__).'/page/index.html';

    $html = file_get_contents($html_file_path);
    echo $html;

  } else if ($request_method === "POST" && $request_url === "/post")  {
    $title = htmlspecialchars($_POST['title']);
    $body = htmlspecialchars($_POST['body']);

    $pg_client = PgConnect::getClient();
    $post_clinet = new PostClient(pdo: $pg_client);
    $payload = new PostPayload(2, $title, $body, 1);
    $post_clinet->createPost($payload);


  } else if ($request_method === "GET" && $request_url === "/huga"){
    echo "huga page";
  }
