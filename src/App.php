<?php
require_once(dirname(__DIR__) . "/src/Route.php");
require_once(dirname(__DIR__) . "/src/lib/Http/Request.php");
require_once(dirname(__DIR__) . "/src/lib/Singleton/PageCompose.php");
require_once(dirname(__DIR__) . "/src/client/PostClient.php");

class App
{
    public function run()
    {
        $request_method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

        $handler = Route::getHandler($request_method, $_SERVER['REQUEST_URI']);
        $handler->run();

//        http_response_code($res['status_code']);
//        header('Content-Type: text/html; charset=utf-8');
//        echo $res['body'];
    }
}
