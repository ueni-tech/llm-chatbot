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
        return view('index', compact('conversationId'));
    }

  public function chat(UserMessageRequest $request, $conversationId = null)
  {
    // conversationIdからConversationを取得
    $conversation = Conversation::find($conversationId);
    
    // Conversationが存在しない場合は新規作成
    if (!$conversation) {
      $conversation = Conversation::create([
        'user_id' => auth()->id(),
        'title' => 'New Conversation',
      ]);
    }

    $client = OpenAI::client(config('services.openai.api_key'));

    $result = $client->chat()->create([
      'model' => 'gpt-3.5-turbo',
      'messages' => [
        ['role' => 'user', 'content' => $request->message],
      ],
    ]);

    $response = $result['choices'][0]['message']['content'];

    return redirect()->route('chat.index', ['conversationId' => $conversation->id])->with('response', $response);
  }
}
