<?php

namespace App\Repository;

use App\Lib\Singleton\PgConnect;
use App\Model\Dto\DetailPostDto;
use App\Model\Dto\IndexPostDto;
use App\Model\Dto\ShowPostDto;
use App\Model\Dto\UpdatePostDto;

class PostRepository implements PostRepositoryInterface
{
    public function __construct(
        public ?\PDO $pdo = null)
    {
        if (is_null($pdo)) $this->pdo = PgConnect::getClient();
    }

    /**
     * @return ShowPostDto[]
     */
    public function getPostList(): array
    {
        $query = "SELECT p.*, array_agg(t.name) AS tags FROM posts p INNER JOIN post_tags pt ON p.id = pt.post_id INNER JOIN tags t ON pt.tag_id = t.id GROUP BY p.id";
        $res = $this->pdo->query($query);
        $raw_post_list = $res->fetchAll(\PDO::FETCH_ASSOC);
        $post_list = [];


        foreach ($raw_post_list as $post) {
            $post_list[] = new ShowPostDto(id: $post["id"], user_id: $post["user_id"], title: $post["title"], body: $post["body"], thumbnail_id: $post["thumbnail_id"], tag_list: self::marchalString(text: $post['tags']));
        }

        return $post_list;
    }

    public function getPostById(int $id): DetailPostDto
    {
        $query = "SELECT posts.*, array_agg(DISTINCT tags.name) AS tags, array_agg(DISTINCT images.url) AS images FROM posts LEFT JOIN post_tags ON post_id = post_tags.post_id LEFT JOIN tags ON post_tags.tag_id = tags.id LEFT JOIN images ON posts.id = images.post_id WHERE posts.id = :id GROUP BY posts.id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $raw_post = $stmt->fetchAll(\PDO::FETCH_ASSOC)[0];

        return new DetailPostDto(id: $raw_post["id"], user_id: $raw_post["user_id"], title: $raw_post["title"], body: $raw_post["body"], thumbnail_id: $raw_post["thumbnail_id"], tag_list: self::marchalString($raw_post["tags"]), image_list: self::marchalString($raw_post["images"]));
    }

    public function createPost(IndexPostDto $payload): int
    {
        $query = "INSERT INTO posts (user_id, title, body, thumbnail_id) VALUES (:user_id, :title, :body, :thumbnail_id) RETURNING id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":user_id", $payload->user_id);
        $stmt->bindParam(":title", $payload->title);
        $stmt->bindParam(":body", $payload->body);
        $stmt->bindParam(":thumbnail_id", $payload->thumbnail_id);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['id'];
    }

    public function updatePost(int $post_id, UpdatePostDto $dto): void
    {
        $query = "UPDATE posts SET title = :title, body = :body, thumbnail_id = :thumbnail_id WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":id", $post_id);
        $stmt->bindParam(":title", $dto->title);
        $stmt->bindParam(":body", $dto->body);
        $stmt->bindParam(":thumbnail_id", $dto->thumbnail_id);
        $stmt->execute();
    }

    public function deletePost(int $post_id): void
    {
        $query = "DELETE FROM posts WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":id", $post_id);
        $stmt->execute();
    }

    /**
     * @param string $text
     * @return string[]
     */
    public static function marchalString(string $text): array
    {
        return explode(",", str_replace("}", "", str_replace("{", "", $text)));
    }
}
