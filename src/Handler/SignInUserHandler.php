<?php

namespace App\Handler;

use App\Lib\HTMLBuilderInterface;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Manager\CsrfManager;
use App\Lib\Manager\SessionManagerInterface;
use App\Repository\UserRepositoryInterface;

class SignInUserHandler implements HandlerInterface
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

        $mail = $this->req->post['mail'];
        $raw_password = $this->req->post['password'];

        $user = $this->user_repo->findUserByEmail(email: $mail);

        if (!is_null($user) && password_verify($raw_password, $user->password)) {
            $_SESSION['user_id'] = $user->id;

            return new Response(status_code: SEE_OTHER_STATUS_CODE, redirect_url: HOST_BASE_URL);
        }

        return new Response(status_code: OK_STATUS_CODE, html: "<div>failed login.</div>");
    }
}
