<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserTest extends TestCase
{

    use WithoutMiddleware;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = \App\Models\User::find(2);
        $this->actingAs($this->user);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testStore()
    {
        $faker = \Faker\Factory::create('ja_JP');
        $response = $this->post('/api/v1/m/user', [
            'name' => $faker->name(),
            'email' => $faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'belong' => '営業部',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);
        $response->assertStatus(201);
    }

    public function testDestroy1()
    {
        $response = $this->delete('/api/v1/m/user/1');
        $response->assertStatus(204);
    }

    public function testDestroy2()
    {
        $response = $this->delete('/api/v1/m/user/999999');
        $response->assertStatus(404);
    }

    public function testDestroy3()
    {
        $response = $this->delete('/api/v1/m/user/2');
        $response->assertStatus(400);
    }

    public function testIndex1()
    {
        $response = $this->get(route('user.index', [
            'sort' => 'id',
            'order' => 'asc'
        ]));
        $response->assertStatus(200);
        $this->assertTrue($response['data'][0]['deleted_at'] === null);
    }

    public function testIndex2()
    {
        $response = $this->get(route('user.index', [
            'is_include_deleted' => 'true',
            'sort' => 'id',
            'order' => 'asc'
        ]));
        $response->assertStatus(200);
        $this->assertTrue($response['data'][0]['deleted_at'] != null);
    }

    public function testShow1()
    {
        $response = $this->get('/api/v1/m/user/3');
        $response->assertStatus(200);
    }

    public function testShow2()
    {
        $response = $this->get('/api/v1/m/user/999999');
        $response->assertStatus(404);
    }

    public function testUpdate1()
    {
        // 情報取得
        $response = $this->get('/api/v1/m/user/2');
        $params = json_decode($response->content(), true);
        $params['name'] = 'ユーザ更新テスト';

        // 情報更新
        $response = $this->put('/api/v1/m/user/2', $params);
        $response->assertStatus(200);
        $this->assertTrue($response['name'] === "ユーザ更新テスト");
    }

    public function testUpdate2()
    {
        // 情報取得
        $response = $this->get('/api/v1/m/user/2');
        $params = json_decode($response->content(), true);

        // 存在しないユーザ
        $response = $this->put('/api/v1/m/user/999999', $params);
        $response->assertStatus(404);
    }
}
