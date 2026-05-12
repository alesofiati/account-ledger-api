<?php

namespace Tests\Unit\Services\Accounts;

use App\Contracts\AccountRepository;
use App\Services\Accounts\ResetAccountsAction;
use PHPUnit\Framework\TestCase;

class ResetAccountsActionTest extends TestCase
{
    public function test_calls_repository_reset(): void
    {
        $repository = $this->createMock(AccountRepository::class);
        $repository->expects($this->once())
            ->method('reset');

        $action = new ResetAccountsAction($repository);
        $action();
    }

    public function test_reset_returns_void(): void
    {
        $repository = $this->createMock(AccountRepository::class);
        $repository->expects($this->once())
            ->method('reset');

        $action = new ResetAccountsAction($repository);
        $result = $action();

        $this->assertNull($result);
    }
}

