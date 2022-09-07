<?php

namespace App\Http\Controllers\Render;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;

class ActionRenderController extends Controller
{
    protected $type = '';
    protected $typeModel ='' ;
    public function destroy($id)
    {
        try {
            $model = $this->typeModel;
            $data = App::make($model)->find($id);
            $data->delete();
            return response()->json(['message' => 'Delete User Successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th], 404);
        }
    }
}
