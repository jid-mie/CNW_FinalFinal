<div>
    <label class="block mb-1 font-medium" for="name">Name</label>
    <input class="w-full border rounded px-3 py-2" id="name" name="name" type="text" value="{{ old('name', $user->name ?? '') }}">
    @error('name')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label class="block mb-1 font-medium" for="email">Email</label>
    <input class="w-full border rounded px-3 py-2" id="email" name="email" type="email" value="{{ old('email', $user->email ?? '') }}">
    @error('email')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label class="block mb-1 font-medium" for="password">Password</label>
    <input class="w-full border rounded px-3 py-2" id="password" name="password" type="password">
    @error('password')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
