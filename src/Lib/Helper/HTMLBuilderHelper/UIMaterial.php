<?php
namespace App\Lib\Helper\HTMLBuilderHelper;

class UIMaterial
{
    public function __construct(
        public string $slot,
        public string $replacement)
    {
    }
}

