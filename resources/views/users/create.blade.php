@extends('layouts.app')

@section('content')
    <div class="bg-white rounded shadow p-6">
        <h1 class="text-2xl font-bold mb-4">Create Customer / Owner</h1>

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
            @csrf
            @include('users.partials.form')

            <button class="bg-blue-600 text-white px-4 py-2 rounded" type="submit">Save</button>
            <a class="ml-2 text-gray-600" href="{{ route('admin.users.index') }}">Cancel</a>
        </form>
    </div>
@endsection
