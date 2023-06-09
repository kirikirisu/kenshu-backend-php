<?php
require_once(dirname(__DIR__) . "/lib/Http/Response.php");

class Handle404
{
    public function run(): Response
    {
        return new Response(status_code: 404, html: "<h1>Page not found.</h1>");
    }
}
