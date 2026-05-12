<?php

namespace App\Services\Accounts;

use App\Contracts\AccountRepository;

readonly class DepositAction
{
    public function __construct(private AccountRepository $accounts)
    {
    }

    public function __invoke(string $destination, int $amount): array
    {
        $balance = ($this->accounts->get($destination) ?? 0) + $amount;
        $this->accounts->set($destination, $balance);

        return [
            'destination' => [
                'id' => $destination,
                'balance' => $balance,
            ],
        ];
    }
}

