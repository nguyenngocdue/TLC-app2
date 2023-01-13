<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $suffix = '@tlcmodular.com';
        if (str_contains($request->email, $suffix)) {
            return response()->json([
                'success' => true,
                'message' => 'Please reset password via TLC password portal.'
            ], 200);
        } else {
            Config::set(
                [
                    'auth.providers.users' =>
                    [
                        "driver" => 'eloquent',
                        'model' => User::class,
                    ]
                ]
            );
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|min:8|max:255',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->messages()
                ], 200);
            }
            // We will send the password reset link to this user. Once we have attempted
            // to send the link, we will examine the response then see the message we
            // need to show to the user. Finally, we'll send out a proper response.
            $response = $this->broker()->sendResetLink(
                $this->credentials($request)
            );
            if ($response == Password::RESET_LINK_SENT) {
                return response()->json([
                    'success' => true,
                    'message' => trans($response),
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => trans($response)
                ], 200);
            }
        }
    }
    /**
     * Get the needed authentication credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only('email');
    }
    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker();
    }
}
