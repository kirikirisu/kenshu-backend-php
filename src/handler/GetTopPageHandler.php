<?php
require_once(dirname(__DIR__, 1) . "/client/PostClient.php");
require_once(dirname(__DIR__, 1) . "/lib/PageComposer.php");
require_once(dirname(__DIR__) . "/client/PostClient.php");

class GetTopPageHandler
{
    public function __construct(
        public PageComposer $compose,
        public PostClient $post_client)
    {
    }

    public function run()
    {
        $post_list = $this->post_client->getPostList();

        $this->compose->topPage($post_list)->renderHTML();
    }
}
