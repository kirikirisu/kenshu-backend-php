<?php
namespace App\Lib\Manager;

// TODO: rename to CsrfValidater and move dir
use App\Lib\Http\SessionManager;

class CsrfManager
{
    const HASH_ALGO = 'sha256';

    public static function generate(): string
    {
        return hash(self::HASH_ALGO, SessionManager::getSessionId());
    }

    public static function validate(string $token): bool
    {
        return self::generate() === $token;
    }
}
