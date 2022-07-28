<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function index()
    {
        return view('mails.index');
    }
    public function sendMail(Request $request)
    {
        Mail::to($request->email)
            ->send(new SendMail($request->only(['email', 'content'])));
        return view('mails.index');
    }
}
