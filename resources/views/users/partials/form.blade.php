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
    <label class="block mb-1 font-medium" for="role_id">Role</label>
    <select class="w-full border rounded px-3 py-2" id="role_id" name="role_id">
        <option value="">-- Select customer / owner --</option>
        @foreach (($roles ?? []) as $role)
            <option value="{{ $role->id }}" {{ (string) old('role_id', optional($user ?? null)->role_id ?? '') === (string) $role->id ? 'selected' : '' }}>
                {{ $role->display_name ?? ucfirst($role->name) }}
            </option>
        @endforeach
    </select>
    @error('role_id')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label class="block mb-1 font-medium" for="phone">Phone</label>
    <input class="w-full border rounded px-3 py-2" id="phone" name="phone" type="text" value="{{ old('phone', $user->phone ?? '') }}">
    @error('phone')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label class="block mb-1 font-medium" for="address">Address</label>
    <input class="w-full border rounded px-3 py-2" id="address" name="address" type="text" value="{{ old('address', $user->address ?? '') }}">
    @error('address')
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
