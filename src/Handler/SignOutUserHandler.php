<?php
namespace App\Handler;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Http\SessionManager;

class SignOutUserHandler implements HandlerInterface
{
    public function __construct(
        public Request $req)
    {
    }

    public function run(): Response
    {
        SessionManager::beginSession();
        $user_id = SessionManager::findValueByKey("user_id");
        if (is_null($user_id)) return new Response(status_code: UNAUTHORIZED_STATUS_CODE, html: "<div>Unauthorized</div>");

        setcookie(SessionManager::getSessionName(), '', time() - 3600);
        SessionManager::regenerateId();
        SessionManager::destroy();

        return new Response(status_code: SEE_OTHER_STATUS_CODE, redirect_url: HOST_BASE_URL);
    }

}
