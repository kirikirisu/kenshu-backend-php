<?php
namespace App\Handler;

use App\handler\HandlerInterface;
use App\Lib\Http\Response;
use App\Lib\Manager\SessionManagerInterface;

class SignOutUserHandler implements HandlerInterface
{
    public function __construct(
        public SessionManagerInterface $session)
    {
    }

    public function run(): Response
    {
        $this->session->beginSession();
        $user_id = $this->session->findValueByKey("user_id");
        if (is_null($user_id)) return new Response(status_code: UNAUTHORIZED_STATUS_CODE, html: "<div>Unauthorized</div>");

        setcookie($this->session->getSessionName(), '', time() - 3600);
        $this->session->regenerateId();
        $this->session->destroy();

        return new Response(status_code: SEE_OTHER_STATUS_CODE, redirect_url: HOST_BASE_URL);
    }

}
