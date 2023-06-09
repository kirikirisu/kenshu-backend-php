<?php

class Response
{
    public function __construct(
        public string $status_code,
        public ?string $html = null,
        public ?string $redirect_url = null,
    )
    {
    }
}
