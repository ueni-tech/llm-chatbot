<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenAI;
use OpenAI\Client;

class Conversation extends Model
{
  use HasFactory;

  protected $fillable = ['user_id', 'title'];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function messages()
  {
    return $this->hasMany(Message::class);
  }

  /**
   * @param int|null $conversationId
   * @return Conversation
   */
  public static function findOrCreate(?int $conversationId): self
  {
    $convasation = self::findOrNew($conversationId);
    if (!$convasation->exists) {
      $convasation->fill([
        'user_id' => auth()->id(),
        'title' => 'Conversation with OpenAI',
      ])->save();
    }
    return $convasation;
  }

  /**
   * @param string $content
   * @return Message
   */
  public function addUserMessage(string $content): Message
  {
    return $this->messages()->create([
      'user_id' => auth()->id(),
      'role' => 'user',
      'content' => $content,
    ]);
  }

  /**
   * @param Conversation $conversation
   * @param Client $openAiClient
   * @return string
   */
  public static function getAiResponse(Conversation $conversation, Client $openAiClient): string
  {
      $result = $openAiClient->chat()->create([
          'model' => 'gpt-3.5-turbo',
          'messages' => $conversation->getMessageHistory(),
      ]);

      return $result['choices'][0]['message']['content'];
  }

  /**
   * @param string $content
   * @return Message
   */
  public function addAssistantMessage(string $content): Message
  {
    return $this->messages()->create([
      'user_id' => auth()->id(),
      'role' => 'assistant',
      'content' => $content,
    ]);
  }


  /**
   * @return array
   */
  public function getMessageHistory(): array
  {
    return $this->messages()
      ->orderBy('created_at', 'asc')
      ->take(10)
      ->get()
      ->map(function ($message) {
        return [
          'role' => $message->role,
          'content' => $message->content,
        ];
      })
      ->toArray();
  }

  public function updateTitle(Client $openAiClient): void
  {
    $titleResponse = $openAiClient->chat()->create([
      'model' => 'gpt-3.5-turbo',
      'messages' => [
        ['role' => 'system', 'content' => 'You are a helpful assistant that generates short, concise titles for conversations.'],
        ...$this->getMessageHistory(),
        ['role' => 'user', 'content' => 'Please generate a short title for this conversation in Japanese. No quoting, etc. is required.'],
      ],
      'max_tokens' => 60,
    ]);

    $title = $titleResponse['choices'][0]['message']['content'];
    $this->update(['title' => trim($title)]);
  }
}
