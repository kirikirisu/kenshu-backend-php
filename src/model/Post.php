<?php
 class Post {
    public int $id;
    public int $user_id;
    public string $title; 
    public string $body;
    public int $thumbnail_id;

    public function __construct(int $id, int $user_id, string $title, string $body, int $thumbnail_id) {
      $this->id = $id;
      $this->user_id = $user_id;
      $this->title = $title;
      $this->body = $body;
      $this->thumbnail_id = $thumbnail_id;
    }
 }
