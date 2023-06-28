<?php

namespace App\Lib\Manager;

use Exception;

class SessionManager
{
    public static function beginSession(): void
    {
        session_start();
    }

    public static function status(): int
    {
        return session_status();
    }

    /**
     * @return string
     * @throws Exception
     */
    public static function getSessionId(): string
    {
        if (self::status() === PHP_SESSION_NONE) throw new \BadMethodCallException('Session is not active.');
        if (!session_id()) throw new Exception("session id not found.");

        return session_id();
    }

    public static function setValue(string $key, string $value): void
    {
        if (self::status() === PHP_SESSION_NONE) throw new \BadMethodCallException('Session is not active.');

        $_SESSION[$key] = $value;
    }

    public static function findValueByKey($key): mixed
    {
        if (self::status() === PHP_SESSION_NONE) throw new \BadMethodCallException('Session is not active.');
        if (!isset($_SESSION[$key])) return null;

        return $_SESSION[$key];
    }

    public static function destroy(): void
    {
        if (self::status() === PHP_SESSION_NONE) throw new \BadMethodCallException('Session is not active.');
        session_destroy();
    }

    public static function regenerateId(): void
    {
        if (self::status() === PHP_SESSION_NONE) throw new \BadMethodCallException('Session is not active.');
        session_regenerate_id(true);
    }

    /**
     * @return string
     * @throws Exception
     */
    public static function getSessionName(): string
    {
        if (!session_name()) throw new Exception("session name not found.");

        return session_name();
    }
}
