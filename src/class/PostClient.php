<?php
  require_once(dirname(__DIR__, 1)."/class/PostPayload.php");

  class PostClient {
    public PDO $pdo;

    public function __construct(PDO $pdo)
    {
      $this->pdo = $pdo;
    }

    public function getPostList() {

    }

    public function getPostById() {
      
    }

    public function createPost(PostPayload $payload) {
      $query = "INSERT INTO posts (user_id, title, body, thumbnail_id) VALUES (:user_id, :title, :body, :thumbnail_id)";
      $stmt = $this->pdo->prepare($query);
      $stmt->bindParam(":user_id", $payload->user_id);
      $stmt->bindParam(":title", $payload->title);
      $stmt->bindParam(":body", $payload->body);
      $stmt->bindParam(":thumbnail_id", $payload->thumbnail_id);
      $stmt->execute();
    }
  }
