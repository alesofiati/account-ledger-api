<?php

namespace Tests\Feature;

use App\Services\AccountService;
use Tests\TestCase;

class EventControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        app(AccountService::class)->reset();
    }

    public function test_create_account_with_initial_balance()
    {
        $response = $this->postJson('/event', [
            'type' => 'deposit',
            'destination' => '100',
            'amount' => 10,
        ]);

        $response->assertStatus(201);

        $response->assertExactJson([
            'destination' => [
                'id' => '100',
                'balance' => 10,
            ],
        ]);
    }

    public function test_deposit_into_existing_account()
    {
        (new AccountService)->deposit('100', 10);

        $this->postJson('/event', [
            'type' => 'deposit',
            'destination' => '100',
            'amount' => 10,
        ])
            ->assertStatus(201)
            ->assertExactJson([
                'destination' => [
                    'id' => '100',
                    'balance' => 20,
                ],
            ]);
    }

    public function test_withdraw_from_non_existing_account()
    {
        $this->postJson('/event', [
            'type' => 'withdraw',
            'origin' => '200',
            'amount' => 10,
        ])
            ->assertNotFound()
            ->assertSee(0);
    }

    public function test_withdraw_from_existing_account()
    {
        (new AccountService)->deposit('100', 20);

        $response = $this->postJson('/event', [
            'type' => 'withdraw',
            'origin' => '100',
            'amount' => 5,
        ]);

        $response->assertStatus(201);

        $response->assertExactJson([
            'origin' => [
                'id' => '100',
                'balance' => 15,
            ],
        ]);
    }

    public function test_transfer_from_existing_account()
    {
        (new AccountService)->deposit('100', 15);

        $this->postJson('/event', [
            'type' => 'transfer',
            'origin' => '100',
            'amount' => 15,
            'destination' => '300',
        ])
            ->assertStatus(201)
            ->assertExactJson([
                'origin' => [
                    'id' => '100',
                    'balance' => 0,
                ],
                'destination' => [
                    'id' => '300',
                    'balance' => 15,
                ],
            ]);
    }

    public function test_transfer_from_non_existing_account()
    {
        $this->postJson('/event', [
            'type' => 'transfer',
            'origin' => '200',
            'amount' => 15,
            'destination' => '300',
        ])
            ->assertNotFound()
            ->assertSee(0);
    }
}
