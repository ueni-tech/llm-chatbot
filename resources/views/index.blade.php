<x-app-layout>
  <div class="flex">
    <!-- サイドバー：会話リスト -->
    <div class="w-1/6 bg-gray-100 p-4">
      <h2 class="text-lg font-semibold mb-4">Conversations</h2>
      <ul>
        @foreach($conversations as $conversation)
          <li class="mb-2">
            <a href="{{ route('chat', $conversation->id) }}" class="text-blue-500 hover:underline">
              {{ $conversation->title ?? 'Conversation ' . $conversation->id }}
            </a>
          </li>
        @endforeach
      </ul>
    </div>

    <!-- メインコンテンツ -->
    <div class="w-5/6 p-4">
      <h1 class="text-2xl font-bold mb-4">OpenAI Chat</h1>
      <form action="{{ route('chat', $conversationId) }}" method="POST">
        @csrf
        <textarea name="message" rows="4" cols="50" required class="w-full p-2 border rounded"></textarea>
        <br>
        <button type="submit" class="text-white bg-blue-500 rounded py-2 px-4 mt-2 hover:bg-blue-600">Send Message</button>
      </form>

      @if($messages)
        <h2 class="text-xl font-semibold mt-6 mb-2">Messages: {{ $title }}</h2>
        <ul class="space-y-2">
          @foreach($messages as $message)
            <li class="p-2 {{ $message['role'] === 'user' ? 'bg-gray-100' : 'bg-blue-100' }} rounded">
              <strong>{{ ucfirst($message['role']) }}:</strong> {{ $message['content'] }}
            </li>
          @endforeach
        </ul>
      @endif
      
      @if(session('response'))
        <h2 class="text-xl font-semibold mt-6 mb-2">Response:</h2>
        <p class="p-2 bg-green-100 rounded">{{ session('response') }}</p>
      @endif
    </div>
  </div>
</x-app-layout>
