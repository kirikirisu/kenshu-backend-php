<?php
require_once(dirname(__DIR__) . "/src/vendor/autoload.php");
require_once(dirname(__DIR__) . "/src/setup.php");

use App\Lib\Http\Request;
use App\Route;

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
