<?php

namespace App\Http\Controllers\Render;

use App\Http\Controllers\Controller;
use App\Models\User;

class DeleteRenderController extends Controller
{
    protected $type = '';
    public function destroy($id)
    {
        try {
            $user = User::find($id);
            $user->delete();
            return response()->json(['message' => 'Delete User Successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th], 404);
        }
    }
}
