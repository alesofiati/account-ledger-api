<?php

namespace Tests\Unit\Services\Accounts;

use App\Contracts\AccountRepository;
use App\Services\Accounts\TransferAction;
use PHPUnit\Framework\TestCase;

class TransferActionTest extends TestCase
{
    public function test_returns_null_when_origin_account_does_not_exist(): void
    {
        $repository = $this->createMock(AccountRepository::class);
        $repository->expects($this->once())
            ->method('get')
            ->with('999')
            ->willReturn(null);

        $repository->expects($this->never())
            ->method('set');

        $action = new TransferAction($repository);
        $result = $action('999', '100', 500);

        $this->assertNull($result);
    }

    public function test_transfers_amount_to_existing_destination(): void
    {
        $repository = $this->createMock(AccountRepository::class);

        $getCalls = [
            ['100', 800],
            ['200', 300],
        ];
        $getCallIndex = 0;

        $repository->expects($this->exactly(2))
            ->method('get')
            ->willReturnCallback(function ($accountId) use ($getCalls, &$getCallIndex) {
                $call = $getCalls[$getCallIndex++];
                $this->assertEquals($call[0], $accountId);
                return $call[1];
            });

        $setCalls = [];
        $repository->expects($this->exactly(2))
            ->method('set')
            ->willReturnCallback(function ($accountId, $balance) use (&$setCalls) {
                $setCalls[] = [$accountId, $balance];
            });

        $action = new TransferAction($repository);
        $result = $action('100', '200', 500);

        $this->assertArrayHasKey('origin', $result);
        $this->assertArrayHasKey('destination', $result);
        $this->assertEquals(300, $result['origin']['balance']);
        $this->assertEquals(800, $result['destination']['balance']);

        // Verify set calls
        $this->assertEquals([['100', 300], ['200', 800]], $setCalls);
    }

    public function test_creates_destination_account_if_not_exists(): void
    {
        $repository = $this->createMock(AccountRepository::class);

        $getCalls = [
            ['100', 1000],
            ['999', null],
        ];
        $getCallIndex = 0;

        $repository->expects($this->exactly(2))
            ->method('get')
            ->willReturnCallback(function ($accountId) use ($getCalls, &$getCallIndex) {
                $call = $getCalls[$getCallIndex++];
                $this->assertEquals($call[0], $accountId);
                return $call[1];
            });

        $setCalls = [];
        $repository->expects($this->exactly(2))
            ->method('set')
            ->willReturnCallback(function ($accountId, $balance) use (&$setCalls) {
                $setCalls[] = [$accountId, $balance];
            });

        $action = new TransferAction($repository);
        $result = $action('100', '999', 400);

        $this->assertEquals(600, $result['origin']['balance']);
        $this->assertEquals(400, $result['destination']['balance']);

        // Verify set calls
        $this->assertEquals([['100', 600], ['999', 400]], $setCalls);
    }

    public function test_allows_transfer_resulting_in_negative_origin_balance(): void
    {
        $repository = $this->createMock(AccountRepository::class);

        $getCalls = [
            ['100', 100],
            ['200', 50],
        ];
        $getCallIndex = 0;

        $repository->expects($this->exactly(2))
            ->method('get')
            ->willReturnCallback(function ($accountId) use ($getCalls, &$getCallIndex) {
                $call = $getCalls[$getCallIndex++];
                $this->assertEquals($call[0], $accountId);
                return $call[1];
            });

        $setCalls = [];
        $repository->expects($this->exactly(2))
            ->method('set')
            ->willReturnCallback(function ($accountId, $balance) use (&$setCalls) {
                $setCalls[] = [$accountId, $balance];
            });

        $action = new TransferAction($repository);
        $result = $action('100', '200', 500);

        $this->assertEquals(-400, $result['origin']['balance']);
        $this->assertEquals(550, $result['destination']['balance']);

        // Verify set calls
        $this->assertEquals([['100', -400], ['200', 550]], $setCalls);
    }

    public function test_transfer_between_same_accounts(): void
    {
        $repository = $this->createMock(AccountRepository::class);

        $getCalls = [
            ['100', 1000],
            ['100', 1000],
        ];
        $getCallIndex = 0;

        $repository->expects($this->exactly(2))
            ->method('get')
            ->willReturnCallback(function ($accountId) use ($getCalls, &$getCallIndex) {
                $call = $getCalls[$getCallIndex++];
                $this->assertEquals($call[0], $accountId);
                return $call[1];
            });

        $setCalls = [];
        $repository->expects($this->exactly(2))
            ->method('set')
            ->willReturnCallback(function ($accountId, $balance) use (&$setCalls) {
                $setCalls[] = [$accountId, $balance];
            });

        $action = new TransferAction($repository);
        $result = $action('100', '100', 500);

        // When transferring to same account, origin balance = 1000 - 500 = 500
        // destination balance = 1000 + 500 = 1500
        $this->assertEquals(500, $result['origin']['balance']);
        $this->assertEquals(1500, $result['destination']['balance']);

        // Verify set calls: first deducts 500, then adds 500 back
        $this->assertEquals([['100', 500], ['100', 1500]], $setCalls);
    }
}

