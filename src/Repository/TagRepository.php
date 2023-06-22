<?php

namespace App\Repository;

use App\Lib\Singleton\PgConnect;
use App\Model\Dto\IndexTagDto;

class TagRepository implements TagRepositoryInterface
{
    public function __construct(
        public ?\PDO $pdo = null)
    {
        if (is_null($pdo)) $this->pdo = PgConnect::getClient();
    }

    /**
     * @param int $post_id
     * @return IndexTagDto[]
     */
    public function getTagListByPostId(int $post_id): array
    {
        $query = "SELECT tags.id, tags.name FROM post_tags INNER JOIN tags ON post_tags.tag_id = tags.id WHERE post_tags.post_id = :post_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":post_id", $post_id);
        $stmt->execute();
        $raw_tag_list = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $tag_list = [];

        foreach ($raw_tag_list as $raw_tag) {
            $tag_list[] = new IndexTagDto(id: $raw_tag['id'], name: $raw_tag['name']);
        }

        return $tag_list;
    }

    /**
     * @return IndexTagDto[]
     */
    public function getTagList(): array
    {
        $tag_list = [];
        $query = "SELECT * from tags";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $raw_tags = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($raw_tags as $tag) {
            $tag_list[] = new IndexTagDto(id: $tag['id'], name: $tag['name']);
        }

        return $tag_list;
    }
}
