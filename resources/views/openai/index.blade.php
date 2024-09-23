<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <title>OpenAI Chat</title>
</head>
<body>
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
</body>
</html>
