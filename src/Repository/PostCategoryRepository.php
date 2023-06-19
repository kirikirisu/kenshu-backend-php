<?php

namespace App\Repository;

use App\Lib\Singleton\PgConnect;

class PostCategoryRepository
{
    public function __construct(
        public ?\PDO $pdo = null)
    {
        if (is_null($pdo)) $this->pdo = PgConnect::getClient();
    }

    /**
     * @param int $post_id
     * @param int[] $category_list
     */
    public function insertMultiCategory(int $post_id, array $category_list)
    {
        $query = "INSERT INTO post_tags (post_id, tag_id) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($query);

        foreach ($category_list as $category) {
            $stmt->bindParam(1, $post_id);
            $stmt->bindParam(2, $category);
            $stmt->execute();
        }
    }
}
