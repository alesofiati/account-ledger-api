<?php

namespace App\Services;

use App\Services\Accounts\DepositAction;
use App\Services\Accounts\GetBalanceAction;
use App\Services\Accounts\ResetAccountsAction;
use App\Storage\MemoryStorage;

class AccountService
{
    private ResetAccountsAction $resetAccounts;

    private GetBalanceAction $getBalance;

    private DepositAction $deposit;

    public function __construct(
        ?ResetAccountsAction $resetAccounts = null,
        ?GetBalanceAction $getBalance = null,
        ?DepositAction $deposit = null
    )
    {
        $this->resetAccounts = $resetAccounts ?? app(ResetAccountsAction::class);
        $this->getBalance = $getBalance ?? app(GetBalanceAction::class);
        $this->deposit = $getBalance ?? app(DepositAction::class);
    }

    public function reset(): void
    {
        ($this->resetAccounts)();
    }

    public function findBalance(string $accountId): ?int
    {
        return ($this->getBalance)($accountId);
    }

    public function deposit(string $destination, int $amount): array
    {
        return ($this->deposit)($destination, $amount);
    }
}
