<?php
require_once(dirname(__DIR__) . "/lib/Http/Response.php");

class NotFoundHandler
{
    public function run(): Response
    {
        return new Response(status_code: NOT_FOUND, html: "<h1>Page not found.</h1>");
    }
}
