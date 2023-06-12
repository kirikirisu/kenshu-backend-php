<?php
namespace App\Lib\Singleton;

use App\Lib\PageComposer;

class PageCompose {
    public static  PageComposer|null $composer = null;

    public static function getComposer(): PageComposer {
        if (is_null(static::$composer)) {
            static::$composer = new PageComposer();
        }

        return static::$composer;
    }
}
