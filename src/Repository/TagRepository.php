<?php

namespace App\Repository;

use App\Lib\Helper\PDOHelper;
use App\Lib\Singleton\PgConnect;
use App\Model\Dto\Tag\IndexTagDto;
use App\Model\Dto\Tag\PostTagListDto;

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

    /**
     * @param int[] $post_id_list
     * @return PostTagListDto[]
     */
    public function getPostTagByPostIdList(array $post_id_list): array
    {
        $placeholder = PDOHelper::generateInClausePlaceholder($post_id_list);
        $query = "SELECT post_tags.post_id, array_agg(tags.name) AS tag_names FROM tags INNER JOIN post_tags ON post_tags.tag_id = tags.id WHERE post_tags.post_id IN ($placeholder) GROUP BY post_tags.post_id";

        $stmt = $this->pdo->prepare($query);
        foreach ($post_id_list as $key => $post_id) {
            $stmt->bindValue($key + 1, $post_id);
        }

        $stmt->execute();
        $raw_post_tag_list = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $post_tag_list = [];
        foreach ($raw_post_tag_list as $raw_post_tag) {
            $post_tag_list[] = new PostTagListDto(post_id: $raw_post_tag['post_id'], tag_list: PDOHelper::convertArrayAggResult($raw_post_tag['tag_names']));
        }

        return $post_tag_list;
    }

    /**
     * @param int $post_id
     * @param int[] $tag_list
     * @return void
     */
    public function insertMultiTag(int $post_id, array $tag_list): void
    {
        $query = "INSERT INTO post_tags (post_id, tag_id) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($query);

        foreach ($tag_list as $category) {
            $stmt->bindParam(1, $post_id);
            $stmt->bindParam(2, $category);
            $stmt->execute();
        }
    }
}
