<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function findByEmail(string $email)
    {
        return User::where('email', $email)->firstOrFail();
    }

    public function findById(int $id)
    {
        return User::findOrFail($id);
    }
}