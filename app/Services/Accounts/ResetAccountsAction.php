<?php

namespace App\Services\Accounts;

use App\Contracts\AccountRepository;

readonly class ResetAccountsAction
{
    public function __construct(private AccountRepository $accounts)
    {
    }

    public function __invoke(): void
    {
        $this->accounts->reset();
    }
}

