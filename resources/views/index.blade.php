<x-app-layout>
  <h1>OpenAI Chat</h1>
  <form action="{{route('chat', $conversationId)}}" method="POST">
    @csrf
    <textarea name="message" rows="4" cols="50" required></textarea>
    <br>
    <button type="submit" class="text-white bg-gray-500 rounded py-1 px-2">Send Message</button>
  </form>

  @if($messages)
  <h2>Messages:{{$title}}</h2>
  <ul>
    @foreach($messages as $message)
    <li>{{ $message['role'] }}: {{ $message['content'] }}</li>
    @endforeach
  </ul>
  @endif
  
  @if(session('response'))
  <h2>Response:</h2>
  <p>{{ session('response') }}</p>
  @endif
</x-app-layout>