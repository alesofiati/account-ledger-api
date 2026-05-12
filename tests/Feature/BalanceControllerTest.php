<?php

namespace Tests\Feature;

use App\Services\AccountService;
use Tests\TestCase;

class BalanceControllerTest extends TestCase
{

    public function test_get_balance_for_non_existing_account(): void
    {
        $this->get(
            route('balance', [
                'account_id' => '1234',
            ])
        )
            ->assertNotFound()
            ->assertSee(0);
    }

    public function test_get_balance_for_existing_account(): void
    {
        $amount = 20;
        $account = '100';
        (new AccountService)->deposit($account, $amount);

        $this->get(
            route('balance', [
                'account_id' => $account,
            ])
        )
            ->assertOk()
            ->assertSee($amount);
    }
}
