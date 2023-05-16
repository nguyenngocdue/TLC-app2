<?php

namespace App\Http\Controllers\Utils;

use App\Models\User;

trait TraitCreateUserSocialite
{
    public function registerUser($data)
    {
        $user = User::where('email', $data->email)->first();
        if (!$user) {
            $user = User::create(
                [
                    'first_name' => $data->user['family_name'] ?? '',
                    'last_name' => $data->user['given_name'] ?? '',
                    'provider' => 'google',
                    'full_name' => $data->name,
                    'name' => $data->name,
                    'email' => $data->email,
                    'settings' => []
                ]
            )->assignRoleSet('guest');
        }
        return $user;
    }
}
