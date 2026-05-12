<?php

namespace Tests\Unit\Services\Accounts;

use App\Contracts\AccountRepository;
use App\Services\Accounts\DepositAction;
use PHPUnit\Framework\TestCase;

class DepositActionTest extends TestCase
{
    public function test_creates_new_account_with_deposit_amount(): void
    {
        $repository = $this->createMock(AccountRepository::class);
        $repository->expects($this->once())
            ->method('get')
            ->with('100')
            ->willReturn(null);

        $repository->expects($this->once())
            ->method('set')
            ->with('100', 500);

        $action = new DepositAction($repository);
        $result = $action('100', 500);

        $this->assertArrayHasKey('destination', $result);
        $this->assertEquals('100', $result['destination']['id']);
        $this->assertEquals(500, $result['destination']['balance']);
    }

    public function test_adds_amount_to_existing_account(): void
    {
        $repository = $this->createMock(AccountRepository::class);
        $repository->expects($this->once())
            ->method('get')
            ->with('100')
            ->willReturn(300);

        $repository->expects($this->once())
            ->method('set')
            ->with('100', 800);

        $action = new DepositAction($repository);
        $result = $action('100', 500);

        $this->assertEquals(800, $result['destination']['balance']);
    }

    public function test_deposit_zero_amount(): void
    {
        $repository = $this->createMock(AccountRepository::class);
        $repository->expects($this->once())
            ->method('get')
            ->with('100')
            ->willReturn(200);

        $repository->expects($this->once())
            ->method('set')
            ->with('100', 200);

        $action = new DepositAction($repository);
        $result = $action('100', 0);

        $this->assertEquals(200, $result['destination']['balance']);
    }

    public function test_deposit_large_amount(): void
    {
        $repository = $this->createMock(AccountRepository::class);
        $repository->expects($this->once())
            ->method('get')
            ->with('100')
            ->willReturn(100);

        $repository->expects($this->once())
            ->method('set')
            ->with('100', 999999);

        $action = new DepositAction($repository);
        $result = $action('100', 999899);

        $this->assertEquals(999999, $result['destination']['balance']);
    }
}

