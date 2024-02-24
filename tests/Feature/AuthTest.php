<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testLogin()
    {
        $formData = [
            'email' => 'jnomura@example.net',
            'password' => 'password',
        ];

        $this->post(route('login'), $formData)->assertStatus(200);
    }

    public function testLoginFailure()
    {
        $formData = [
            'email' => 'jnomura@example.net',
            'password' => 'password111111',
        ];

        $this->post(route('login'), $formData)->assertUnauthorized();
    }

    public function testLogout()
    {
        $formData = [
            'email' => 'jnomura@example.net',
            'password' => 'password',
        ];
        $token = json_decode($this->post(route('login'), $formData)->getContent(), true)['access_token'];
        $this->post( route('logout'), $headers=[
            'Authorization' => 'Bearer'. $token
        ])->assertStatus(204);
    }

    public function testLogoutFailure()
    {
        $this->post( route('logout'))->assertStatus(400);
    }

    public function testMe()
    {
        $formData = [
            'email' => 'jnomura@example.net',
            'password' => 'password',
        ];
        $token = json_decode($this->post(route('login'), $formData)->getContent(), true)['access_token'];
        $this->get(route('me'), [
            'Authorization' => 'Bearer'. $token
        ])->assertStatus(200);
    }

    public function testMeFailure()
    {
        $this->get(route('me'))->assertStatus(401);
    }
}
