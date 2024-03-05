<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\TraitCreateUserSocialite;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;


class SocialiteAuthController extends Controller
{
    use TraitCreateUserSocialite;

    public function redirectToGoogle()
    {
        return Socialite::with('google_api')->stateless()->redirect()->getTargetUrl();
    }
    public function handleGoogleCallback()
    {
        $user = Socialite::with('google_api')->stateless()->user();
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
        return $this->registerUser($data);
    }
}
