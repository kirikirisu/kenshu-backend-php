<?php 
  require_once(dirname(__DIR__, 1)."/class/PgConnect.php");
  require_once(dirname(__DIR__, 1)."/class/PostClient.php");
  require_once(dirname(__DIR__, 1)."/class/PostPayload.php");

  $title = htmlspecialchars($_POST['title']);
  $body = htmlspecialchars($_POST['body']);

  $pg_client = PgConnect::getClient();
  $post_clinet = new PostClient(pdo: $pg_client);
  $payload = new PostPayload(2, $title, $body, 1);
  $post_clinet->createPost($payload);
?>
