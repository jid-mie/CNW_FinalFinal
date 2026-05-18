<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'CNW Play Management') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">
    <nav class="bg-white border-b border-gray-200 mb-6">
        <div class="max-w-5xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ route('users.index') }}" class="font-bold text-xl">CNW Play Management</a>
            <a href="{{ route('users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Create User</a>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4">
        @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
