<?php
 class Post {
    public function __construct(
      public int $id, 
      public int $user_id, 
      public string $title, 
      public string $body, 
      public int $thumbnail_id) {
    }
 }