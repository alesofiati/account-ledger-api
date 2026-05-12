<?php

namespace Tests\Unit\Services\Accounts;

use App\Contracts\AccountRepository;
use App\Services\Accounts\GetBalanceAction;
use PHPUnit\Framework\TestCase;

class GetBalanceActionTest extends TestCase
{
    public function test_returns_balance_for_existing_account(): void
    {
        $repository = $this->createMock(AccountRepository::class);
        $repository->expects($this->once())
            ->method('get')
            ->with('100')
            ->willReturn(1000);

        $action = new GetBalanceAction($repository);
        $result = $action('100');

        $this->assertEquals(1000, $result);
    }

    public function test_returns_null_for_non_existing_account(): void
    {
        $repository = $this->createMock(AccountRepository::class);
        $repository->expects($this->once())
            ->method('get')
            ->with('999')
            ->willReturn(null);

        $action = new GetBalanceAction($repository);
        $result = $action('999');

        $this->assertNull($result);
    }

    public function test_returns_zero_balance(): void
    {
        $repository = $this->createMock(AccountRepository::class);
        $repository->expects($this->once())
            ->method('get')
            ->with('100')
            ->willReturn(0);

        $action = new GetBalanceAction($repository);
        $result = $action('100');

        $this->assertEquals(0, $result);
    }
}

