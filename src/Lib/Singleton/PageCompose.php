<?php

namespace App\Lib\Singleton;

use App\Lib\HTMLBuilder;

class PageCompose
{
    public static HTMLBuilder|null $composer = null;

    public static function getComposer(): HTMLBuilder
    {
        if (is_null(static::$composer)) {
            static::$composer = new HTMLBuilder();
        }

        return static::$composer;
    }
}
