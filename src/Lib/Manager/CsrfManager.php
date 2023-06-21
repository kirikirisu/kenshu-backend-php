<?php
namespace App\Lib\Manager;

class CsrfManager
{
    const HASH_ALGO = 'sha256';

    public static function generate(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            throw new \BadMethodCallException('Session is not active.');
        }
        return hash(self::HASH_ALGO, session_id());
    }

    public static function validate($token): bool
    {
        return self::generate() === $token;
    }
}
