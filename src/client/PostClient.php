<?php
require_once(dirname(__DIR__, 1) . "/model/dto/IndexPostDto.php");
require_once(dirname(__DIR__, 1) . "/model/Post.php");
require_once(dirname(__DIR__, 1) . "/lib/Singleton/PgConnect.php");

class PostClient
{
    public function __construct(
        public ?PDO $pdo = null)
    {
        if (is_null($pdo)) $this->pdo = PgConnect::getClient();
    }

    /**
     * @return Post[]
     */
    public function getPostList(): array
    {
        $query = "SELECT * from posts ORDER BY id DESC";
        $res = $this->pdo->query($query);
        $raw_post_list = $res->fetchAll(PDO::FETCH_ASSOC);
        $post_list = [];

        foreach ($raw_post_list as $post) {
            $post_list[] = new Post(id: $post["id"], user_id: $post["user_id"], title: $post["title"], body: $post["body"], thumbnail_id: $post["thumbnail_id"]);
        }

        return $post_list;
    }

    public function getPostById(string $id): Post
    {
        $query = "SELECT * from posts WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $raw_post = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];

        return new Post(id: $raw_post["id"], user_id: $raw_post["user_id"], title: $raw_post["title"], body: $raw_post["body"], thumbnail_id: $raw_post["thumbnail_id"]);
    }

    public function createPost(IndexPostDto $payload): void
    {
        $query = "INSERT INTO posts (user_id, title, body, thumbnail_id) VALUES (:user_id, :title, :body, :thumbnail_id)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":user_id", $payload->user_id);
        $stmt->bindParam(":title", $payload->title);
        $stmt->bindParam(":body", $payload->body);
        $stmt->bindParam(":thumbnail_id", $payload->thumbnail_id);
        $stmt->execute();
    }
}
