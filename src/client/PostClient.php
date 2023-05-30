<?php
require_once(dirname(__DIR__, 1) . "/client/PostPayload.php");
require_once(dirname(__DIR__, 1) . "/model/Post.php");
require_once(dirname(__DIR__, 1) . "/lib/PgConnect.php");

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

    public function getPostById()
    {

    }

    public function createPost(PostPayload $payload): void
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
