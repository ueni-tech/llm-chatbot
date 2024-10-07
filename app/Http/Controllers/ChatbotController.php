<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserMessageRequest;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use OpenAI;

use function Livewire\Volt\title;

class ChatbotController extends Controller
{
  public function index($conversationId = null)
  {
    $messages = [];
    if ($conversationId) {
      $conversation = Conversation::find($conversationId);
      $title = $conversation->title;
      $messages = $conversation->messages()->orderBy('created_at', 'asc')->get();
    }

    return view('index', compact('conversationId', 'title', 'messages'));
  }

  public function chat(UserMessageRequest $request, $conversationId = null)
  {
    // conversationの作成
    $conversation = Conversation::find($conversationId);

    if (!$conversation) {
      $conversation = Conversation::create([
        'user_id' => auth()->id(),
        'title' => 'New Conversation',
      ]);
    }

    // userからのmessageの作成
    Message::create([
      'user_id' => auth()->id(),
      'conversation_id' => $conversation->id,
      'content' => $request->message,
      'role' => 'user',
    ]);

    $client = OpenAI::client(config('services.openai.api_key'));

    $result = $client->chat()->create([
      'model' => 'gpt-3.5-turbo',
      'messages' => $conversation->getMessageHistory(),
    ]);

    $aiResponse = $result['choices'][0]['message']['content'];

    // aiからのmessageの作成
    Message::create([
      'user_id' => auth()->id(),
      'conversation_id' => $conversation->id,
      'content' => $aiResponse,
      'role' => 'assistant',
    ]);

    // conversationのtitleを更新
    if ($conversation->messages()->count() === 2) {
      $titleResponse = $client->chat()->create([
        'model' => 'gpt-3.5-turbo',
        'messages' => [
          ['role' => 'system', 'content' => 'You are a helpful assistant that generates short, concise titles for conversations.'],
          $conversation->getMessageHistory()[0],
          $conversation->getMessageHistory()[1],
          ['role' => 'user', 'content' => 'Please generate a short title for this conversation in Japanese.No quoting, etc. is required.'],
        ],
        'max_tokens' => 60,
      ]);
      $title = $titleResponse['choices'][0]['message']['content'];
      $conversation->update(['title' => trim($title)]);
    }

    return redirect()->route('chat.index', ['conversationId' => $conversation->id]);
  }
}
