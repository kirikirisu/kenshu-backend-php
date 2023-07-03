<?php

namespace App\Handler;

use App\Lib\Error\FileErrorType;
use App\Lib\Helper\ImageBinaryStoreHelper;
use App\Lib\HTMLBuilderInterface;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Http\SessionManager;
use App\Lib\Manager\CsrfManager;
use App\Lib\Validator\ValidateImageFile;
use App\Lib\Validator\ValidateUser;
use App\Model\Dto\User\IndexUserDto;
use App\Repository\UserRepositoryInterface;

class CreateUserHandler implements HandlerInterface
{
    public function __construct(
        public Request                 $req,
        public HTMLBuilderInterface    $compose,
        public UserRepositoryInterface $user_repo)
    {
    }

    public function run(): Response
    {
        SessionManager::beginSession();
        if (!CsrfManager::validate(token: $this->req->post['csrf'])) return new Response(status_code: UNAUTHORIZED_STATUS_CODE, html: "<div>エラーが発生しました。</div>");

        $name = $this->req->post['name'];
        $mail = $this->req->post['mail'];
        $raw_password = $this->req->post['password'];
        $avatar = $this->req->files['avatar'];

        //TODO: regular validation, avoid duplicate emails
        $error_list = ValidateUser::exec(name: $name, mail: $mail, password: $raw_password);
        if (count($error_list) > 0) {
            $html = $this->compose->signUpPage(csrf_token: CsrfManager::generate(), error_list: $error_list)->getHtml();
            return new Response(status_code: UNPROCESSABLE_ENTITY_STATUS_CODE, html: $html);
        }

        $file_error = ValidateImageFile::validateSingleFile($avatar);
        if (!is_null($file_error) && $file_error->type !== FileErrorType::NOT_SELECTED) {
            return new Response(status_code: UNPROCESSABLE_ENTITY_STATUS_CODE, html: "<div>{$file_error->message}</div>");
        }

        $icon_url = null;
        if (!$file_error->type == FileErrorType::NOT_SELECTED) {
            $stored_file_path_list = ImageBinaryStoreHelper::storeToDisk(filename: $avatar['name'], src_file_path: $avatar['tmp_name']);
            $icon_url = $stored_file_path_list->root_relative_path;
        }

        $hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);
        $payload = new IndexUserDto(name: $name, email: $mail, password: $hashed_password, icon_url: $icon_url);
//        return new Response(status_code: OK_STATUS_CODE, html: "$icon_url");
        $user_id = $this->user_repo->insertUser($payload);

        SessionManager::setValue(key: "user_id", value: $user_id);

        return new Response(status_code: SEE_OTHER_STATUS_CODE, redirect_url: HOST_BASE_URL);
    }
}
