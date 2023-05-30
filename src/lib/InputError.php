<?php
class InputError {
    public string $message;
    public string $field;

    public function __construct(string $message, string $field)
    {
        $this->field = $field;
        $this->message = $message;
    }
}
