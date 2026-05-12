<?php

namespace App\Storage;

use App\Contracts\AccountRepository;

class MemoryAccountRepository implements AccountRepository
{
    public function reset(): void
    {
        MemoryStorage::$accounts = [];
    }

    public function get(string $accountId): ?int
    {
        return MemoryStorage::$accounts[$accountId] ?? null;
    }

    public function set(string $accountId, int $balance): void
    {
        MemoryStorage::$accounts[$accountId] = $balance;
    }

    public function exists(string $accountId): bool
    {
        return isset(MemoryStorage::$accounts[$accountId]);
    }
}

