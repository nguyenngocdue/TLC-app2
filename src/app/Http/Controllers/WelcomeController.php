<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Hashing\AbstractHasher;
use Illuminate\Support\Facades\Hash;

class WelcomeController extends Controller
{
    public function index()
    {
        $password = Hash::make('123456', ['salt' => 'random']);
        dump($password);
        $password2 = Hash::make('123456', ['salt' => 'random2']);
        dump($password2);

        $isCheck = Hash::check('123456', $password2, ['salt' => 'random']);
        dd($isCheck);
        return view(
            'welcome',
            []
        );
    }
}
