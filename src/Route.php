<?php

namespace App;

use App\Handler\CreatePostHandler;
use App\Handler\CreateUserHandler;
use App\Handler\DeletePostHandler;
use App\Handler\GetPostDetailPageHandler;
use App\Handler\GetPostEditPageHandler;
use App\Handler\GetSignInPageHandler;
use App\Handler\GetSignUpPageHandler;
use App\Handler\GetTopPageHandler;
use App\Handler\HandlerInterface;
use App\Handler\NotFoundHandler;
use App\Handler\SignInUserHandler;
use App\Handler\SignOutUserHandler;
use App\Handler\UpdatePostHandler;
use App\Lib\Http\Request;
use App\Lib\Singleton\PageCompose;
use App\Lib\Singleton\PgConnect;
use App\Repository\ImageRepository;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;

class Route
{
    public static function getHandler(Request $req): HandlerInterface
    {
        if ($req->method === "GET" && preg_match("/^\/(\?([a-zA-Z0-9_]+=[a-zA-Z0-9_]+&)*[a-zA-Z0-9_]+=[a-zA-Z0-9_]+)?$/", $req->path)) {
            return new GetTopPageHandler(req: $req, compose: PageCompose::getComposer(), user_repo: new UserRepository(), tag_repo: new TagRepository(), post_repo: new PostRepository());

        } else if ($req->method === "POST" && $req->path === "/") {
            $pdo = PgConnect::getClient();
            return new CreatePosthandler(req: $req, pdo: $pdo, compose: PageCompose::getComposer(), user_repo: new UserRepository(), post_repo: new PostRepository(pdo: $pdo), image_repo: new ImageRepository(pdo: $pdo), tag_repo: new TagRepository(pdo: $pdo));

        } else if ($req->method === "GET" && preg_match("|\A/posts/([0-9]+)\z|u", $req->path, $match)) {
            $post_id = (int)$match[1];
            $pdo = PgConnect::getClient();
            return new GetPostDetailPageHandler(pdo: $pdo, post_id: $post_id, compose: PageCompose::getComposer(), post_repo: new PostRepository(pdo: $pdo), image_repo: new ImageRepository(pdo: $pdo), tag_repo: new TagRepository(pdo: $pdo));

        } else if ($req->method === "GET" && preg_match("|\A/posts/([0-9]+)/edit\z|u", $req->path, $match)) {
            $post_id = (int)$match[1];
            $pdo = PgConnect::getClient();
            return new GetPostEditPageHandler(pdo: $pdo, post_id: $post_id, req: $req, compose: PageCompose::getComposer(), post_repo: new PostRepository(pdo: $pdo), image_repo: new ImageRepository(pdo: $pdo), tag_repo: new TagRepository(pdo: $pdo));

        } else if ($req->method === "PATCH" && preg_match("|\A/posts/([0-9]+)/edit\z|u", $req->path, $match)) {
            $post_id = (int)$match[1];
            return new UpdatePostHandler(req: $req, post_id: $post_id, compose: PageCompose::getComposer(), post_repo: new PostRepository());

        } else if ($req->method === "DELETE" && preg_match("|\A/posts/([0-9]+)/edit\z|u", $req->path, $match)) {
            $post_id = (int)$match[1];
            return new DeletePostHandler(post_id: $post_id, req: $req, post_repo: new PostRepository());

        } else if ($req->method === "GET" && $req->path === "/user/signup") {
            return new GetSignUpPageHandler(req: $req, compose: PageCompose::getComposer());

        } else if ($req->method === "POST" && $req->path === "/user/signup") {
            return new CreateUserHandler(req: $req, compose: PageCompose::getComposer(), user_repo: new UserRepository());

        } else if ($req->method === "GET" && $req->path === "/user/signin") {
            return new GetSignInPageHandler(req: $req, compose: PageCompose::getComposer());

        } else if ($req->method === "POST" && $req->path === "/user/signin") {
            return new SignInUserHandler(req: $req, compose: PageCompose::getComposer(), user_repo: new UserRepository());

        } else if ($req->method === "POST" && $req->path === "/user/signout") {
            return new SignOutUserHandler(req: $req);

        }

        return new NotFoundHandler();
    }
}
