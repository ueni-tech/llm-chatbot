<x-app-layout>
    <h1>OpenAI Chat</h1>
    <form action="{{route('chat')}}" method="POST">
        @csrf
        <textarea name="message" rows="4" cols="50" required></textarea>
        <br>
        <button type="submit" class="text-white bg-gray-500 rounded py-1 px-2">Send Message</button>
    </form>

    @if(isset($response))
        <h2>Response:</h2>
        <p>{{ $response }}</p>
    @endif
</x-app-layout>
