<?php

namespace Tests\Unit\Services\Accounts;

use App\Contracts\AccountRepository;
use App\Services\Accounts\WithdrawAction;
use PHPUnit\Framework\TestCase;

class WithdrawActionTest extends TestCase
{
    public function test_returns_null_for_non_existing_account(): void
    {
        $repository = $this->createMock(AccountRepository::class);
        $repository->expects($this->once())
            ->method('get')
            ->with('999')
            ->willReturn(null);

        $repository->expects($this->never())
            ->method('set');

        $action = new WithdrawAction($repository);
        $result = $action('999', 100);

        $this->assertNull($result);
    }

    public function test_withdraws_amount_from_existing_account(): void
    {
        $repository = $this->createMock(AccountRepository::class);
        $repository->expects($this->once())
            ->method('get')
            ->with('100')
            ->willReturn(500);

        $repository->expects($this->once())
            ->method('set')
            ->with('100', 200);

        $action = new WithdrawAction($repository);
        $result = $action('100', 300);

        $this->assertArrayHasKey('origin', $result);
        $this->assertEquals('100', $result['origin']['id']);
        $this->assertEquals(200, $result['origin']['balance']);
    }

    public function test_allows_withdrawal_that_results_in_negative_balance(): void
    {
        $repository = $this->createMock(AccountRepository::class);
        $repository->expects($this->once())
            ->method('get')
            ->with('100')
            ->willReturn(100);

        $repository->expects($this->once())
            ->method('set')
            ->with('100', -500);

        $action = new WithdrawAction($repository);
        $result = $action('100', 600);

        $this->assertEquals(-500, $result['origin']['balance']);
    }

    public function test_withdraw_entire_balance(): void
    {
        $repository = $this->createMock(AccountRepository::class);
        $repository->expects($this->once())
            ->method('get')
            ->with('100')
            ->willReturn(500);

        $repository->expects($this->once())
            ->method('set')
            ->with('100', 0);

        $action = new WithdrawAction($repository);
        $result = $action('100', 500);

        $this->assertEquals(0, $result['origin']['balance']);
    }
}

