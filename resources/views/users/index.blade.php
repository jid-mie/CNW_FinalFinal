@extends('layouts.app')

@section('content')
    <div class="bg-white rounded shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">Customer / Owner</h1>
            <a class="bg-blue-600 text-white px-4 py-2 rounded" href="{{ route('admin.users.create') }}">Create User</a>
        </div>

        <table class="w-full border-collapse">
            <thead>
                <tr class="border-b text-left">
                    <th class="py-2">ID</th>
                    <th class="py-2">Name</th>
                    <th class="py-2">Email</th>
                    <th class="py-2">Role</th>
                    <th class="py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="border-b">
                        <td class="py-2">{{ $user->id }}</td>
                        <td class="py-2">{{ $user->name }}</td>
                        <td class="py-2">{{ $user->email }}</td>
                        <td class="py-2">{{ $user->role?->display_name ?? ucfirst($user->role?->name ?? '-') }}</td>
                        <td class="py-2 flex gap-2">
                            <a class="text-blue-600" href="{{ route('admin.users.show', $user) }}">View</a>
                            <a class="text-yellow-600" href="{{ route('admin.users.edit', $user) }}">Edit</a>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete this user?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-4 text-center text-gray-500">No managed users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
@endsection
