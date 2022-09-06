<?php

namespace App\Http\Controllers\Render;

use App\Http\Controllers\Controller;

class ActionRenderController extends Controller
{
    protected $type = '';
    protected $data ;
    public function destroy($id)
    {
        try {
            $data = $this->data::find($id);
            $data->delete();
            return response()->json(['message' => 'Delete User Successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th], 404);
        }
    }
}
