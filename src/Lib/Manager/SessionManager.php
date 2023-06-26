<?php

namespace App\Lib\Manager;

use Exception;

class SessionManager implements SessionManagerInterface
{
    // なかったらsessionを作成、 そのsessionに紐ずくsessionId の発行、ブラウザのcookieにsessionIdをセット
    public function beginSession(): void
    {
        session_start();
    }

    public function status(): int
    {
        return session_status();
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getSessionId(): string
    {
        if (!session_id()) throw new Exception("session id not found.");

        return session_id();
    }

    // sessionId の session に key, valueで値をセット
    public function setValue(string $key, string $value): void
    {
        if ($this->status() === PHP_SESSION_NONE) throw new \BadMethodCallException('Session is not active.');

        $_SESSION[$key] = $value;
    }

    public function findValueByKey($key): mixed
    {
        if ($this->status() === PHP_SESSION_NONE) throw new \BadMethodCallException('Session is not active.');

        return $_SESSION[$key];
    }
}
