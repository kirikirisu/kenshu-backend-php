<?php
namespace App\Lib\Manager;

// TODO: rename to CsrfValidater and move dir
class CsrfManager
{
    const HASH_ALGO = 'sha256';

    public static function generate(SessionManager $session): string
    {
        return hash(self::HASH_ALGO, $session->getSessionId());
    }

    public static function validate(SessionManager $session, string $token): bool
    {
        return self::generate($session) === $token;
    }
}
