<x-app-layout>
    <h1>OpenAI Chat</h1>
    <form action="{{ route('openai.send') }}" method="POST">
        @csrf
        <textarea name="message" rows="4" cols="50" required></textarea>
        <br>
        <button type="submit">Send Message</button>
    </form>

    @if(isset($response))
        <h2>Response:</h2>
        <p>{{ $response }}</p>
    @endif
</x-app-layout>
