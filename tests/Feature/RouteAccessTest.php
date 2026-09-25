<?php

namespace Tests\Feature;

use Tests\TestCase;

class RouteAccessTest extends TestCase
{
    public function test_login_page_uses_existing_auth_view(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertViewIs('auth.login');
    }

    public function test_radiology_routes_are_available(): void
    {
        $this->get('/radiologi')
            ->assertOk();

        $this->get('/poli/radiologi')
            ->assertRedirect('/radiologi');
    }
}
