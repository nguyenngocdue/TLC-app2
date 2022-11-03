<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;


class SocialiteAuthController extends Controller
{

    public function redirectToGoogle()
    {
        return Socialite::with('google')->stateless()->redirect()->getTargetUrl();
    }
    public function handleGoogleCallback()
    {
        $user = Socialite::with('google')->stateless()->user();
        $newUser = $this->_registerAndLoginUser($user);
        $token = $newUser->createToken('tlc_token')->plainTextToken;
        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'message' => 'Successfully created user'
        ], 200);
    }

    protected function _registerAndLoginUser($data)
    {
        $user = User::updateOrCreate(
            [
                'first_name' => (string)$data->user['family_name'],
                'last_name' => (string)$data->user['given_name'],
                'provider' => (string)('google'),
                'name' => (string)$data->name,
                'full_name' => (string)$data->name,
                'email' => (string)$data->email,
                'settings' => []
            ]
        );
        return $user;
    }
}
