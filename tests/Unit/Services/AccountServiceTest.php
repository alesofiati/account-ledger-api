<?php

namespace Tests\Unit\Services;

use App\Services\AccountService;
use App\Services\Accounts\DepositAction;
use App\Services\Accounts\GetBalanceAction;
use App\Services\Accounts\ResetAccountsAction;
use App\Services\Accounts\TransferAction;
use App\Services\Accounts\WithdrawAction;
use PHPUnit\Framework\TestCase;

class AccountServiceTest extends TestCase
{
    private function createAccountService(
        ?ResetAccountsAction $resetAccounts = null,
        ?GetBalanceAction $getBalance = null,
        ?DepositAction $deposit = null,
        ?WithdrawAction $withdraw = null,
        ?TransferAction $transfer = null,
    ): AccountService {
        $resetAccounts = $resetAccounts ?? $this->createMock(ResetAccountsAction::class);
        $getBalance = $getBalance ?? $this->createMock(GetBalanceAction::class);
        $deposit = $deposit ?? $this->createMock(DepositAction::class);
        $withdraw = $withdraw ?? $this->createMock(WithdrawAction::class);
        $transfer = $transfer ?? $this->createMock(TransferAction::class);

        return new AccountService(
            $resetAccounts,
            $getBalance,
            $deposit,
            $withdraw,
            $transfer,
        );
    }

    public function test_reset_delegates_to_reset_action(): void
    {
        $resetAction = $this->createMock(ResetAccountsAction::class);
        $resetAction->expects($this->once())->method('__invoke');

        $service = $this->createAccountService(resetAccounts: $resetAction);
        $service->reset();
    }

    public function test_find_balance_delegates_to_get_balance_action(): void
    {
        $getBalanceAction = $this->createMock(GetBalanceAction::class);
        $getBalanceAction->expects($this->once())
            ->method('__invoke')
            ->with('100')
            ->willReturn(500);

        $service = $this->createAccountService(getBalance: $getBalanceAction);
        $result = $service->findBalance('100');

        $this->assertEquals(500, $result);
    }

    public function test_deposit_delegates_to_deposit_action(): void
    {
        $depositAction = $this->createMock(DepositAction::class);
        $expectedResult = ['destination' => ['id' => '100', 'balance' => 500]];
        $depositAction->expects($this->once())
            ->method('__invoke')
            ->with('100', 500)
            ->willReturn($expectedResult);

        $service = $this->createAccountService(deposit: $depositAction);
        $result = $service->deposit('100', 500);

        $this->assertEquals($expectedResult, $result);
    }

    public function test_withdraw_delegates_to_withdraw_action(): void
    {
        $withdrawAction = $this->createMock(WithdrawAction::class);
        $expectedResult = ['origin' => ['id' => '100', 'balance' => 300]];
        $withdrawAction->expects($this->once())
            ->method('__invoke')
            ->with('100', 200)
            ->willReturn($expectedResult);

        $service = $this->createAccountService(withdraw: $withdrawAction);
        $result = $service->withdraw('100', 200);

        $this->assertEquals($expectedResult, $result);
    }

    public function test_withdraw_returns_null_on_non_existing_account(): void
    {
        $withdrawAction = $this->createMock(WithdrawAction::class);
        $withdrawAction->expects($this->once())
            ->method('__invoke')
            ->with('999', 200)
            ->willReturn(null);

        $service = $this->createAccountService(withdraw: $withdrawAction);
        $result = $service->withdraw('999', 200);

        $this->assertNull($result);
    }

    public function test_transfer_delegates_to_transfer_action(): void
    {
        $transferAction = $this->createMock(TransferAction::class);
        $expectedResult = [
            'origin' => ['id' => '100', 'balance' => 300],
            'destination' => ['id' => '200', 'balance' => 700],
        ];
        $transferAction->expects($this->once())
            ->method('__invoke')
            ->with('100', '200', 400)
            ->willReturn($expectedResult);

        $service = $this->createAccountService(transfer: $transferAction);
        $result = $service->transfer('100', '200', 400);

        $this->assertEquals($expectedResult, $result);
    }

    public function test_transfer_returns_null_on_non_existing_origin(): void
    {
        $transferAction = $this->createMock(TransferAction::class);
        $transferAction->expects($this->once())
            ->method('__invoke')
            ->with('999', '200', 400)
            ->willReturn(null);

        $service = $this->createAccountService(transfer: $transferAction);
        $result = $service->transfer('999', '200', 400);

        $this->assertNull($result);
    }
}

