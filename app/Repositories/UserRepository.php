<?php
namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function get($userId)
    {
        $user = $this->user->where('id',$userId)
            ->select('surname','name','avatar')
            ->firstOrFail();

        return $user;
    }
}