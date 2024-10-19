<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
  use RefreshDatabase;

  protected User $user;

  protected function setUp(): void
  {
    parent::setUp();

    $this->user = User::factory()->create([
      'name' => 'Test User',
      'email' => 'test@example.com',
      'password' => bcrypt('password'),
    ]);
  }

  /**
   * @test
   */
  public function 非ログインユーザーのログインテスト(): void
  {
    $response = $this->get('/');

    $response->assertStatus(302);
  }

  /**
   * @test
   */
  public function ログインユーザーのログインテスト(): void
  {
    $response = $this->actingAs($this->user)->get('/');
    $response->assertStatus(200);
  }
}
