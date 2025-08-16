<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-g">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Carrots</title>
    <style> /* Basic styling */ body { font-family: sans-serif; container { max-width: 600px; margin: 40px auto; } } </style>
</head>
<body>
<div class="container">
    <h1>My Carrots</h1>

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <form action="{{ route('carrots.store') }}" method="POST">
        @csrf
        <h3>Add a New Carrot</h3>
        <div>
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>
        </div>
        <div>
            <label for="length">Length (cm):</label>
            <input type="number" name="length" id="length" required>
        </div>
        <button type="submit">Add Carrot</button>
    </form>

    <hr>

    <h2>Existing Carrots</h2>
    @forelse ($carrots as $carrot)
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
            <p>{{ $carrot->name }} ({{ $carrot->length }}cm) - <em>Added: {{ $carrot->created_at->diffForHumans() }}</em></p>
            <form action="{{ route('carrots.destroy', $carrot) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        </div>
    @empty
        <p>You don't have any carrots yet.</p>
    @endforelse
</div>
</body>
</html>