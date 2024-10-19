<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use OpenAI\Client;
use Tests\TestCase;

class ConversationTest extends TestCase
{
  use RefreshDatabase;

  protected User $user;
  private Client $openAiClient;

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
  public function findOrCreateのcreateテスト(): void
  {
    $this->actingAs($this->user);

    $conversation = Conversation::findOrCreate(null);
    $this->assertDatabaseHas('conversations', [
      'id' => $conversation->id,
      'user_id' => $this->user->id,
      'title' => 'Conversation with OpenAI',
    ]);
  }

  /**
   * @test
   */
  public function findOrCreateのfindテスト(): void
  {
    $this->actingAs($this->user);

    $conversation = Conversation::create([
      'user_id' => $this->user->id,
      'title' => 'This is a test conversation',
    ]);

    $foundConversation = Conversation::findOrCreate($conversation->id);
    $this->assertDatabaseHas('conversations', [
      'id' => $foundConversation->id,
      'user_id' => $foundConversation->user_id,
      'title' => $foundConversation->title,
    ]);
  }
}
