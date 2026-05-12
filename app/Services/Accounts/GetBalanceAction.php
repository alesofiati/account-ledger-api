<?php

namespace App\Services\Accounts;

use App\Contracts\AccountRepository;

readonly class GetBalanceAction
{
    public function __construct(private AccountRepository $accounts)
    {
    }

    public function __invoke(string $accountId): ?int
    {
        return $this->accounts->get($accountId);
    }
}

