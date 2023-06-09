<?php

class GetPostDetailPageHandler
{
    public function __construct(
        public int $post_id,
        public PageComposer $compose,
        public PostClient $post_client)
    {
    }

    public function run()
    {
        $post = $this->post_client->getPostById($this->post_id);

        $this->compose->postDetailPage($post)->renderHTML();
    }
}
