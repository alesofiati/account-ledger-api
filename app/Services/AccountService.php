<?php

namespace App\Services;

use App\Services\Accounts\ResetAccountsAction;
use App\Storage\MemoryStorage;

class AccountService
{
    private ResetAccountsAction $resetAccounts;

    public function __construct(
        ?ResetAccountsAction $resetAccounts = null
    )
    {
        $this->resetAccounts = $resetAccounts ?? app(ResetAccountsAction::class);
    }

    public function reset(): void
    {
        ($this->resetAccounts)();
    }
}
