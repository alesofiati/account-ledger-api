<?php

namespace App\Services;

use App\Storage\MemoryStorage;

class AccountService
{
    public function reset(): void
    {
        MemoryStorage::$accounts = [];
    }

    public function findBalance(string $accountId): ?int
    {
        return MemoryStorage::$accounts[$accountId] ?? null;
    }

    public function deposit(string $destination, int $amount): array
    {
        if (! isset(MemoryStorage::$accounts[$destination])) {
            MemoryStorage::$accounts[$destination] = 0;
        }

        MemoryStorage::$accounts[$destination] += $amount;

        return [
            'destination' => [
                'id' => $destination,
                'balance' => MemoryStorage::$accounts[$destination],
            ],
        ];
    }

    public function withdraw(string $origin, int $amount): ?array
    {
        if (! isset(MemoryStorage::$accounts[$origin])) {
            return null;
        }

        MemoryStorage::$accounts[$origin] -= $amount;

        return [
            'origin' => [
                'id' => $origin,
                'balance' => MemoryStorage::$accounts[$origin],
            ],
        ];
    }

    public function transfer(string $origin, string $destination, int $amount): ?array
    {
        if (! isset(MemoryStorage::$accounts[$origin])) {
            return null;
        }

        if (! isset(MemoryStorage::$accounts[$destination])) {
            MemoryStorage::$accounts[$destination] = 0;
        }

        MemoryStorage::$accounts[$origin] -= $amount;

        MemoryStorage::$accounts[$destination] += $amount;

        return [
            'origin' => [
                'id' => $origin,
                'balance' => MemoryStorage::$accounts[$origin],
            ],
            'destination' => [
                'id' => $destination,
                'balance' => MemoryStorage::$accounts[$destination],
            ],
        ];
    }
}
