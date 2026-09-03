<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BasicRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_is_accessible(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
    }

    public function test_login_page_is_accessible_for_guests(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk();
    }
}
