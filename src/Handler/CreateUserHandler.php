<?php

namespace App\Handler;

use App\Lib\Helper\ImageBinaryStoreHelper;
use App\Lib\HTMLBuilderInterface;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Model\Dto\User\IndexUserDto;
use App\Repository\UserRepositoryInterface;

class CreateUserHandler implements HandlerInterface
{
    public function __construct(
        public Request              $req,
        public HTMLBuilderInterface $compose,
        public UserRepositoryInterface $user_repo)
    {
    }

    public function run(): Response
    {
        $name = $this->req->post['name'];
        $mail = $this->req->post['mail'];
        $password = $this->req->post['password'];
        $avatar = $this->req->files['avatar'];

        $stored_file_path_list = ImageBinaryStoreHelper::storeToDisk(filename: $avatar['name'], src_file_path:  $avatar['tmp_name']);
        $payload = new IndexUserDto(name: $name, email: $mail, password: $password, icon_url: $stored_file_path_list->root_relative_path);
        $this->user_repo->insertUser($payload);

        return new Response(status_code: SEE_OTHER_STATUS_CODE, redirect_url: HOST_BASE_URL);
    }
}
