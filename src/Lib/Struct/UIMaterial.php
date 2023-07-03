<?php
namespace App\Lib\Struct;

class UIMaterial
{
    public function __construct(
        public string $slot,
        public string $replacement)
    {
    }
}

