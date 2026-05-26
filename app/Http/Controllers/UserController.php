<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Http\Requests\User\CreateUserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    private const MANAGED_ROLE_NAMES = [
        RoleEnum::CUSTOMER->value,
        RoleEnum::OWNER->value,
    ];

    public function index(): View
    {
        $users = User::with('role')
            ->whereHas('role', function ($query): void {
                $query->whereIn('name', self::MANAGED_ROLE_NAMES);
            })
            ->latest()
            ->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        $roles = Role::whereIn('name', self::MANAGED_ROLE_NAMES)
            ->orderBy('name')
            ->get();

        return view('users.create', compact('roles'));
    }

    public function store(CreateUserRequest $request): RedirectResponse
    {
        User::create($request->validated());

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user): View
    {
        $this->ensureManagedUser($user);

        return view('users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        $this->ensureManagedUser($user);

        $roles = Role::whereIn('name', self::MANAGED_ROLE_NAMES)
            ->orderBy('name')
            ->get();

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(CreateUserRequest $request, User $user): RedirectResponse
    {
        $this->ensureManagedUser($user);

        $user->update($request->validated());

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->ensureManagedUser($user);

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    private function ensureManagedUser(User $user): void
    {
        abort_unless($user->hasRole(self::MANAGED_ROLE_NAMES), 404);
    }
}
