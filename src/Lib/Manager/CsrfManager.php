<?php
namespace App\Lib\Manager;

class CsrfManager
{
    const HASH_ALGO = 'sha256';

    public static function generate(SessionManager $session): string
    {
        if ($session->status() === PHP_SESSION_NONE) {
            throw new \BadMethodCallException('Session is not active.');
        }
        return hash(self::HASH_ALGO, $session->getSessionId());
    }

    public static function validate(SessionManager $session, string $token): bool
    {
        return self::generate($session) === $token;
    }
}
