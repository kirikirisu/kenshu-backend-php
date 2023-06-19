<?php

namespace App\Repository;

use App\Lib\Singleton\PgConnect;

class CategoryRepository
{
    public function __construct(
        public ?\PDO $pdo = null)
    {
        if (is_null($pdo)) $this->pdo = PgConnect::getClient();
    }

    /**
     * @param int[] $category_list
     */
    public function insertMultiCategory(array $category_list)
    {
        $query = "INSERT INTO images (post_id, url) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($query);

        foreach ($category_list as $image) {
            $stmt->bindParam(1, $post_id);
            $stmt->bindParam(2, $image);
            $stmt->execute();
        }
    }
}
