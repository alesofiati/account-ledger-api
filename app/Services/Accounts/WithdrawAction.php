<?php

namespace App\Services\Accounts;

use App\Contracts\AccountRepository;

readonly class WithdrawAction
{
    public function __construct(private AccountRepository $accounts)
    {
    }

    public function __invoke(string $origin, int $amount): ?array
    {
        $currentBalance = $this->accounts->get($origin);

        if ($currentBalance === null) {
            return null;
        }

        $balance = $currentBalance - $amount;
        $this->accounts->set($origin, $balance);

        return [
            'origin' => [
                'id' => $origin,
                'balance' => $balance,
            ],
        ];
    }
}

