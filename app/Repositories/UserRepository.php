<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    public function create(array $data)
    {
        $data['password'] = Hash::make($data['password']);

        return $this->user->create($data);
    }

    public function getByEmail(string $email)
    {
        return $this->user->where('email', $email)->first();
    }
}
