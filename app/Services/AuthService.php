<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data)
    {
        return $this->userRepository->create($data);
    }

    public function login(array $credentials)
    {
        if (!Auth::attempt($credentials)) {
            return null;
        }

        return $this->userRepository->findByEmail($credentials['email']);
    }

    public function findUserById(int $id)
    {
        return $this->userRepository->findById($id);
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
    }
}