<?php
namespace App\handler;

use App\Lib\Http\Response;

interface HandlerInterface
{
    public function run(): Response;
}
