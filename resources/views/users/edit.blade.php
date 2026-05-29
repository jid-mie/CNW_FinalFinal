@extends('layouts.app')

@section('content')
    <div class="bg-white rounded shadow p-6">
        <h1 class="text-2xl font-bold mb-4">Edit Customer / Owner</h1>

        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            @include('users.partials.form')

            <button class="bg-blue-600 text-white px-4 py-2 rounded" type="submit">Update</button>
            <a class="ml-2 text-gray-600" href="{{ route('admin.users.index') }}">Cancel</a>
        </form>
    </div>
@endsection
