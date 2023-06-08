<?php

class ImageClient
{
    public function __construct(
        public ?PDO $pdo = null)
    {
        if (is_null($pdo)) $this->pdo = PgConnect::getClient();
    }

    public function createMultiImageForPost(int $post_id, array $image_list)
    {
        $query = "INSERT INTO images (post_id, url) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($query);

        foreach ($image_list as $image) {
            $stmt->bindParam(1,$post_id);
            $stmt->bindParam(2, $image);
            $stmt->execute();
        }
    }

}
