<?php

namespace Tests\Unit\Storage;

use App\Storage\MemoryAccountRepository;
use App\Storage\MemoryStorage;
use PHPUnit\Framework\TestCase;

class MemoryAccountRepositoryTest extends TestCase
{
    protected function tearDown(): void
    {
        // Clean up after each test
        MemoryStorage::$accounts = [];
        parent::tearDown();
    }

    public function test_reset_clears_all_accounts(): void
    {
        MemoryStorage::$accounts = ['100' => 500, '200' => 300];

        $repository = new MemoryAccountRepository();
        $repository->reset();

        $this->assertEmpty(MemoryStorage::$accounts);
    }

    public function test_get_returns_balance_for_existing_account(): void
    {
        MemoryStorage::$accounts = ['100' => 500];

        $repository = new MemoryAccountRepository();
        $balance = $repository->get('100');

        $this->assertEquals(500, $balance);
    }

    public function test_get_returns_null_for_non_existing_account(): void
    {
        MemoryStorage::$accounts = [];

        $repository = new MemoryAccountRepository();
        $balance = $repository->get('999');

        $this->assertNull($balance);
    }

    public function test_set_creates_new_account(): void
    {
        MemoryStorage::$accounts = [];

        $repository = new MemoryAccountRepository();
        $repository->set('100', 500);

        $this->assertEquals(500, MemoryStorage::$accounts['100']);
    }

    public function test_set_updates_existing_account(): void
    {
        MemoryStorage::$accounts = ['100' => 300];

        $repository = new MemoryAccountRepository();
        $repository->set('100', 700);

        $this->assertEquals(700, MemoryStorage::$accounts['100']);
    }

    public function test_exists_returns_true_for_existing_account(): void
    {
        MemoryStorage::$accounts = ['100' => 500];

        $repository = new MemoryAccountRepository();
        $exists = $repository->exists('100');

        $this->assertTrue($exists);
    }

    public function test_exists_returns_false_for_non_existing_account(): void
    {
        MemoryStorage::$accounts = [];

        $repository = new MemoryAccountRepository();
        $exists = $repository->exists('999');

        $this->assertFalse($exists);
    }

    public function test_set_negative_balance(): void
    {
        MemoryStorage::$accounts = [];

        $repository = new MemoryAccountRepository();
        $repository->set('100', -500);

        $this->assertEquals(-500, MemoryStorage::$accounts['100']);
    }

    public function test_set_zero_balance(): void
    {
        MemoryStorage::$accounts = ['100' => 500];

        $repository = new MemoryAccountRepository();
        $repository->set('100', 0);

        $this->assertEquals(0, MemoryStorage::$accounts['100']);
    }
}

