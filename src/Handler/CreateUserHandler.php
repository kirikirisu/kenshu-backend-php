<?php

namespace App\Handler;

use App\Lib\Helper\ImageBinaryStoreHelper;
use App\Lib\HTMLBuilderInterface;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Manager\CsrfManager;
use App\Lib\Manager\SessionManagerInterface;
use App\Model\Dto\User\IndexUserDto;
use App\Repository\UserRepositoryInterface;

class CreateUserHandler implements HandlerInterface
{
    public function __construct(
        public Request                 $req,
        public SessionManagerInterface $session,
        public HTMLBuilderInterface    $compose,
        public UserRepositoryInterface $user_repo)
    {
    }

    public function run(): Response
    {
        $this->session->beginSession();
        if (!CsrfManager::validate(session: $this->session, token: $this->req->post['csrf'])) return new Response(status_code: OK_STATUS_CODE, html: "<div>エラーが発生しました。</div>");


        $name = $this->req->post['name'];
        $mail = $this->req->post['mail'];
        $raw_password = $this->req->post['password'];
        $avatar = $this->req->files['avatar'];
        //TODO: regular validation, avoid duplicate emails

        $hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);
        $stored_file_path_list = ImageBinaryStoreHelper::storeToDisk(filename: $avatar['name'], src_file_path: $avatar['tmp_name']);
        $payload = new IndexUserDto(name: $name, email: $mail, password: $hashed_password, icon_url: $stored_file_path_list->root_relative_path);
        $user_id = $this->user_repo->insertUser($payload);

        $this->session->setValue(key: "user_id", value: $user_id);

        return new Response(status_code: SEE_OTHER_STATUS_CODE, redirect_url: HOST_BASE_URL);
    }
}
