<?php

namespace App\Services;

use App\Services\Accounts\DepositAction;
use App\Services\Accounts\GetBalanceAction;
use App\Services\Accounts\ResetAccountsAction;
use App\Services\Accounts\TransferAction;
use App\Services\Accounts\WithdrawAction;

class AccountService
{
    private ResetAccountsAction $resetAccounts;

    private GetBalanceAction $getBalance;

    private DepositAction $deposit;

    private WithdrawAction $withdraw;

    private TransferAction $transfer;

    public function __construct(
        ?ResetAccountsAction $resetAccounts = null,
        ?GetBalanceAction $getBalance = null,
        ?DepositAction $deposit = null,
        ?WithdrawAction $withdraw = null,
        ?TransferAction $transfer = null,
    ) {
        $this->resetAccounts = $resetAccounts ?? app(ResetAccountsAction::class);
        $this->getBalance = $getBalance ?? app(GetBalanceAction::class);
        $this->deposit = $deposit ?? app(DepositAction::class);
        $this->withdraw = $withdraw ?? app(WithdrawAction::class);
        $this->transfer = $transfer ?? app(TransferAction::class);
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

    public function withdraw(string $origin, int $amount): ?array
    {
        return ($this->withdraw)($origin, $amount);
    }

    public function transfer(string $origin, string $destination, int $amount): ?array
    {
        return ($this->transfer)($origin, $destination, $amount);
    }
}
