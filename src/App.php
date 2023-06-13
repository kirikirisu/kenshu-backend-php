<?php
require_once(dirname(__DIR__) . "/src/setup.php");
require_once(dirname(__DIR__) . "/src/Route.php");
require_once(dirname(__DIR__) . "/src/lib/Http/Request.php");
require_once(dirname(__DIR__) . "/src/lib/Singleton/PageCompose.php");
require_once(dirname(__DIR__) . "/src/client/PostClient.php");

class App
{
    public function run()
    {
        $request_method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

        $req = new Request(method: $request_method, path: $_SERVER['REQUEST_URI'], post: $_POST);
        $handler = Route::getHandler(req: $req);
        $res = $handler->run();

        if (is_null($res->html)) {
            $redirect_header = sprintf('Location: %s', $res->redirect_url);
            header($redirect_header, true, $res->status_code);
        }

        http_response_code($res->status_code);
        header('Content-Type: text/html; charset=utf-8');
        echo $res->html;
    }
}
