<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserMessageRequest;
use App\Models\Conversation;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use OpenAI\Client;

class ChatbotController extends Controller
{
    private Client $openAiClient;

    public function __construct(Client $openAiClient)
    {
        $this->openAiClient = $openAiClient;
    }

    public function index(?int $conversationId = null): View
    {
        $conversations = Conversation::where('user_id', auth()->id())->get();
        $conversation = $conversationId ? Conversation::findOrFail($conversationId) : null;
        $messages = $conversation ? $conversation->messages()->orderBy('created_at', 'asc')->get() : [];
        $title = $conversation?->title ?? '';

        return view('index', compact('conversations', 'conversationId', 'title', 'messages'));
    }

    /**
     * @param UserMessageRequest $request
     * @param int|null $conversationId
     * @return RedirectResponse
     */
    public function chat(UserMessageRequest $request, ?int $conversationId = null): RedirectResponse
    {
        $conversation = Conversation::findOrCreate($conversationId);
        $conversation->addUserMessage($request->message);

        $aiResponse = Conversation::getAiResponse($conversation, $this->openAiClient);
        $conversation->addAssistantMessage($aiResponse);

        if ($conversation->messages()->count() === 2) {
            $conversation->updateTitle($this->openAiClient);
        }

        return redirect()->route('chat.index', ['conversationId' => $conversation->id]);
    }
}
