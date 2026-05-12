<?php

namespace App\Contracts;

interface AccountRepository
{
    public function reset(): void;

    public function get(string $accountId): ?int;

    public function set(string $accountId, int $balance): void;

    public function exists(string $accountId): bool;
}

