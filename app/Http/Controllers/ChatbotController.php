<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserMessageRequest;
use Illuminate\Http\Request;
use OpenAI;

class ChatbotController extends Controller
{
    public function index($conversationId = null)
    {
        return view('index', compact('conversationId'));
    }

  public function chat(UserMessageRequest $request, $conversationId = null)
  {
    $client = OpenAI::client(config('services.openai.api_key'));

    $result = $client->chat()->create([
      'model' => 'gpt-3.5-turbo',
      'messages' => [
        ['role' => 'user', 'content' => $request->message],
      ],
    ]);

    $response = $result['choices'][0]['message']['content'];

    $conversationId = $request->conversation_id;

    return view('index', compact('response', 'conversationId'));
  }
}
