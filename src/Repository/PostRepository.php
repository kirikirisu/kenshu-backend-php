<?php

namespace App\Repository;

use App\Lib\Singleton\PgConnect;
use App\Model\Dto\Post\IndexPostDto;
use App\Model\Dto\Post\ShowPostDto;
use App\Model\Dto\Post\UpdatePostDto;

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
        $query = "SELECT posts.*, images.url AS thumbnail_url, users.name AS user_name, users.icon_url AS user_avatar FROM posts INNER JOIN images ON posts.thumbnail_id = images.id INNER JOIN users ON users.id = posts.user_id";
        $res = $this->pdo->query($query);
        $raw_post_list = $res->fetchAll(\PDO::FETCH_ASSOC);
        $post_list = [];

        foreach ($raw_post_list as $raw_post) {
            $post_list[] = new ShowPostDto(id: (int)$raw_post["id"], user_id: (int)$raw_post["user_id"], title: $raw_post["title"], body: $raw_post["body"], thumbnail_id: (int)$raw_post["thumbnail_id"], thumbnail_url: $raw_post["thumbnail_url"], user_name: $raw_post["user_name"], user_avatar: $raw_post['user_avatar']);
        }

        return $post_list;
    }

    public function getPostById(int $id): ShowPostDto
    {
        $query = "SELECT posts.*, images.url AS thumbnail_url, users.name AS user_name, users.icon_url AS user_avatar FROM posts INNER JOIN images ON posts.thumbnail_id = images.id INNER JOIN users ON users.id = posts.user_id WHERE posts.id = :id";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $raw_post = $stmt->fetchAll(\PDO::FETCH_ASSOC)[0];

        return new ShowPostDto(id: (int)$raw_post["id"], user_id: (int)$raw_post["user_id"], title: $raw_post["title"], body: $raw_post["body"], thumbnail_id: $raw_post["thumbnail_id"], thumbnail_url: $raw_post["thumbnail_url"], user_name: $raw_post["user_name"], user_avatar: $raw_post['user_avatar']);
    }

    public function insertPost(IndexPostDto $payload): int
    {
        $query = "INSERT INTO posts (user_id, title, body) VALUES (:user_id, :title, :body) RETURNING id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":user_id", $payload->user_id);
        $stmt->bindParam(":title", $payload->title);
        $stmt->bindParam(":body", $payload->body);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['id'];
    }

    public function updateThumbnail(int $post_id, int $thumbnail_id): void
    {
        $query = "UPDATE posts SET thumbnail_id = :thumbnail_id WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(param: ":id", var: $post_id);
        $stmt->bindParam(param: ":thumbnail_id", var: $thumbnail_id);
        $stmt->execute();
    }

    public function updatePost(int $post_id, UpdatePostDto $dto): void
    {
        $query = "UPDATE posts SET title = :title, body = :body WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":id", $post_id);
        $stmt->bindParam(":title", $dto->title);
        $stmt->bindParam(":body", $dto->body);
        $stmt->execute();
    }

    public function deletePost(int $post_id): void
    {
        $query = "DELETE FROM posts WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":id", $post_id);
        $stmt->execute();
    }
}
