@extends('layouts.app')

@section('content')
    <div class="bg-white rounded shadow p-6">
        <h1 class="text-2xl font-bold mb-4">Managed User Detail</h1>

        <div class="space-y-2">
            <p><strong>ID:</strong> {{ $user->id }}</p>
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Role:</strong> {{ $user->role?->display_name ?? ucfirst($user->role?->name ?? '-') }}</p>
            <p><strong>Phone:</strong> {{ $user->phone ?? '-' }}</p>
            <p><strong>Address:</strong> {{ $user->address ?? '-' }}</p>
        </div>

        <div class="mt-4">
            <a class="text-yellow-600" href="{{ route('admin.users.edit', $user) }}">Edit</a>
            <a class="ml-2 text-gray-600" href="{{ route('admin.users.index') }}">Back</a>
        </div>
    </div>
@endsection
