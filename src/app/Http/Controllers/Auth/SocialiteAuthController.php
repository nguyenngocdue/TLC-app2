<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\TraitCreateUserSocialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteAuthController extends Controller
{
    use TraitCreateUserSocialite;

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $user  = Socialite::driver('google')->user();

        $this->_registerOrLoginUser($user);

        return redirect('/dashboard');
    }
    protected function _registerOrLoginUser($data)
    {
        $user = $this->registerUser($data);

        Auth::login($user);
    }
}
