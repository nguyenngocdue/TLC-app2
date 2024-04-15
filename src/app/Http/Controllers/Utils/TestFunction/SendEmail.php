<?php

namespace App\Http\Controllers\Utils\TestFunction;

use App\Mail\MailTest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class SendEmail
{
    public static function sendTestEmail(Request $request)
    {
        if (!$request->has('email')) {
            dump('Please enter your email address on url params');
            return;
        }
        $email = $request->input('email');
        try {
            Mail::to($email)->send(new MailTest());
        } catch (\Exception $e) {
            dump("Mail Failed to send. Message: " . $e->getMessage());
            return;
        }
        dump('Test Mail Successful! Please check email test in mail ' . $email);
        return;
    }
}
