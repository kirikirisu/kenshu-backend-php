<?php

namespace App\Repository;

use App\Lib\Singleton\PgConnect;
use App\Model\Dto\IndexImageDto;

class ImageRepository implements ImageRepositoryInterface
{
    public function __construct(
        public ?\PDO $pdo = null)
    {
        if (is_null($pdo)) $this->pdo = PgConnect::getClient();
    }

    public function insertImage(int $post_id, string $img_path): int
    {
        $query = "INSERT INTO images (post_id, url) VALUES (:post_id, :img_path) RETURNING id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":post_id", $post_id);
        $stmt->bindParam(":img_path", $img_path);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['id'];
    }

    public function getImageById(int $image_id): IndexImageDto
    {
        $query = "SELECT * FROM images WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":id", $image_id);
        $stmt->execute();
        $raw_image = $stmt->fetchAll(\PDO::FETCH_ASSOC)[0];

        return new IndexImageDto(id: $raw_image['id'], post_id: $raw_image['post_id'], url: $raw_image['url']);
    }

    public function insertMultiImageForPost(int $post_id, array $image_list)
    {
        $query = "INSERT INTO images (post_id, url) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($query);

        foreach ($image_list as $image) {
            $stmt->bindParam(1, $post_id);
            $stmt->bindParam(2, $image);
            $stmt->execute();
        }
    }
}
