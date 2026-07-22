<?php

namespace App\Services;

use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function paginated(): LengthAwarePaginator
    {
        return User::with('role')->latest()->paginate(15);
    }

    public function store(StoreUserRequest $request): User
    {
        return User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => $request->role_id,
        ]);
    }

    public function update(User $user, UpdateUserRequest $request): User
    {
        $data = $request->only(['name', 'email', 'role_id']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return $user->fresh('role');
    }

    public function destroy(User $user, User $actor): void
    {
        abort_if($user->id === $actor->id, 422, 'You cannot delete your own account.');

        $user->tokens()->delete();
        $user->delete();
    }
}
