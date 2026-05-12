<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\App;
use Tests\TestCase;

class ResetControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_reset_state_before_starting_tests(): void
    {
        $this->get('reset')
            ->assertStatus(200)
            ->assertContent('');

        $this->assertEmpty(App::make('App\Storage\MemoryStorage')::$accounts);
    }
}
