<?php

namespace App\Storage;

use App\Contracts\AccountRepository;
use Illuminate\Support\Facades\Cache;

class MemoryAccountRepository implements AccountRepository
{
    public function reset(): void
    {
        Cache::clear();
    }

    public function get(string $accountId): ?int
    {
        return Cache::get($accountId);
    }

    public function set(string $accountId, int $balance): void
    {
        Cache::put($accountId, $balance);
    }

    public function exists(string $accountId): bool
    {
        return Cache::has($accountId);
    }
}

