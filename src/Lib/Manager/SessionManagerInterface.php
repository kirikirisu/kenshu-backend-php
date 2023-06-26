<?php
namespace App\Lib\Manager;

use Exception;

interface SessionManagerInterface
{
    public function beginSession(): void;

    public function status(): int;

    /**
     * @return string
     * @throws Exception
     */
    public function getSessionId(): string;

    public function setValue(string $key, string $value): void;

    public function findValueByKey($key): mixed;

    public function destroy(): void;

    public function regenerateId(): void;

    /**
     * @return string
     * @throws Exception
     */
    public function getSessionName(): string;
}
