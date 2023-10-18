<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Utils\Support\CurrentUser;
use App\Utils\System\Api\ResponseObject;
use App\Utils\System\GetSetCookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use LdapRecord\Laravel\Auth\ListensForLdapBindFailure;

class AuthController extends Controller
{
    use ListensForLdapBindFailure;

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255|min:3',
            'last_name' => 'required|string|max:255|min:3',
            'email' => 'required|string|min:8|max:255|unique:users',
            'password' => 'required|string|min:8|max:255|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->messages()
            ], 200);
        } else {

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'name' => $request->first_name . " " . $request->last_name,
                'full_name' => $request->first_name . " " . $request->last_name,
                'email' => $request->email,
                'password' =>  bcrypt($request->password),
                'settings' => []
            ]);
            $token = $user->createToken('tlc_token')->plainTextToken;
            return ResponseObject::responseTokenAndUser($token, clone $user, $user, 'Login Successfully');
        }
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
            if ($this->attemptLogin($request)) {
                $user = User::where('email', $request['email'])->first();
                $token = $user->createToken('tlc_token')->plainTextToken;
                if ($user) {
                    GetSetCookie::setCookieForever('time_zone', $user->time_zone);
                }
                return ResponseObject::responseTokenAndUser($token, clone $user, $user, 'Login Successfully');
            }
        } else {

            $user = User::where('email', $fields['email'])->first();
            if (!$user || !Hash::check($fields['password'], $user->password)) {
                return response([
                    'success' => false,
                    'message' => 'Login failed'
                ], 401);
            }

            GetSetCookie::setCookieForever('time_zone', $user->time_zone);
            $token = $user->createToken('tlc_token')->plainTextToken;

            return ResponseObject::responseTokenAndUser($token, clone $user, $user, 'Login Successfully');
        }
    }
    public function details()
    {
        return response()->json(['success' => true, 'user' => auth()->user()], 200);
    }
    public function logout(Request $request)
    {
        try {
            if (GetSetCookie::hasCookie('time_zone')) {
                GetSetCookie::forgetCookie('time_zone');
            }
            $request->user()->tokens()->delete();
            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th
            ], 401);
        }
    }
    public function user(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user()
        ], 200);
    }
    public function verify(Request $request)
    {
        return response()->json([
            'success' => true,
            'verify' => $request->user() ? true : false,
        ], 200);
    }
    public function email()
    {
        return 'email';
    }
    protected function credentials(Request $request)
    {
        return [
            'userprincipalname' => $request->email,
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
    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
