<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use LdapRecord\Laravel\Auth\ListensForLdapBindFailure;

class AuthController extends Controller
{
    use AuthenticatesUsers, ListensForLdapBindFailure;

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'name_rendered' => $request->first_name . " " . $request->last_name,
            'full_name' => $request->first_name . " " . $request->last_name,
            'email' => $request->email,
            'password' =>  bcrypt($request->password),
            'settings' => []
        ]);
        $token = $user->createToken('tlc_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'message' => 'Successfully created user'
        ], 200);
    }
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
            'remember_me' => 'boolean',
        ]);
        $suffix = '@tlcmodular.com';
        if (str_contains($fields['email'], $suffix)) {
            $this->validateLogin($request);
            $credentials = [
                'email' => $fields['email'],
                'password' => $fields['password'],
            ];
            if ($this->attemptLogin($request)) {
                response()->json(['abc' => 'oke']);
            }

            return response()->json(['abc' => Auth::attempt(['email' => $fields['email'], 'password' => $fields['password']])]);
        } else {
            $user = User::where('email', $fields['email'])->first();
            if (!$user || !Hash::check($fields['password'], $user->password)) {
                return response([
                    'message' => 'Login failed'
                ], 401);
            }
            $token = $user->createToken('myapptoken')->plainTextToken;
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'message' => 'Login Successfully'
            ]);
        }
    }
    public function details()
    {
        return response()->json(['user' => auth()->user()], 200);
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    }
    public function user(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ], 200);
    }
    public function email()
    {
        return 'email';
    }
    protected function credentials(Request $request)
    {
        return [
            'mail' => $request->email,
            'password' => $request->password,
            'fallback' => [
                'email' => $request->email,
                'password' => $request->password,
            ],
        ];
    }
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request),
            $request->boolean('remember')
        );
    }
    public function username()
    {
        return 'email';
    }
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }
}
