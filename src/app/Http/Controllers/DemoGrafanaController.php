<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Utils\Support\AttachmentName;
use Illuminate\Http\Request;

class DemoGrafanaController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {
        $users = User::all();
        $formattedUsers = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'date_of_birth' => $user->date_of_birth,
            ];
        });
        return response()->json(['users' => $formattedUsers], 200);
    }
}
