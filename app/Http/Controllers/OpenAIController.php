<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI;

class OpenAIController extends Controller
{
    public function index()
    {
        return view('openai.index');
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $client = OpenAI::client(config('services.openai.api_key'));

        $result = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $request->message],
            ],
        ]);

        $response = $result['choices'][0]['message']['content'];

        return view('openai.index', compact('response'));
    }
}
