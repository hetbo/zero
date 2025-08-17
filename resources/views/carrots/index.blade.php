<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">All Carrots</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($carrots as $carrot)
                <div class="bg-white rounded-lg shadow-md p-6 border">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $carrot->name }}</h3>
                    <p class="text-gray-600 mb-4">Length: {{ $carrot->length }}cm</p>
                    <div class="text-sm text-gray-500">
                        Created: {{ $carrot->created_at->format('M j, Y') }}
                    </div>
                </div>
            @endforeach
        </div>

        @if($carrots->isEmpty())
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg">No carrots found.</p>
            </div>
        @endif
    </div>
</body>
</html>