<?php

namespace App\Handler;

use App\Lib\Helper\ImageBinaryStoreHelper;
use App\Lib\HTMLBuilderInterface;
use App\Lib\Http\Request;
use App\Lib\Http\Response;

class CreateUserHandler implements HandlerInterface
{
    public function __construct(
        public Request              $req,
        public HTMLBuilderInterface $compose)
    {
    }

    public function run(): Response
    {
        $name = $this->req->post['name'];
        $mail = $this->req->post['mail'];
        $password = $this->req->post['password'];
        $avatar = $this->req->files['avatar'];

        $stored_file_path_list = ImageBinaryStoreHelper::storeToDisk(filename: $avatar['name'], src_file_path:  $avatar['tmp_name']);
        return new Response(status_code: OK_STATUS_CODE, html: "<div>hoge</div>");
    }
}
