<?php

namespace App\Service;

use App\User;

class UserService
{
    public function getUser($id)
    {
        $result = User::select('id', 'nickname', 'email', 'created_at')
            ->where('id', $id)
            ->first();

        return $result;
    }
}