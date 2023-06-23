<?php

namespace App\Lib\Singleton;

use App\Lib\HTMLBuilder;
use App\Lib\HTMLBuilderInterface;

class PageCompose
{
    public static HTMLBuilderInterface|null $composer = null;

    public static function getComposer(): HTMLBuilderInterface
    {
        if (is_null(static::$composer)) {
            static::$composer = new HTMLBuilder();
        }

        return static::$composer;
    }
}
