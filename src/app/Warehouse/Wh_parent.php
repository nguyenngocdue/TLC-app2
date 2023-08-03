<?php

namespace App\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class Wh_parent extends Controller {


    
    protected function upsertDW($month){
        return false;
    }

    protected function makeNewDataWh(){
        return [];
    }

    public function index(Request $request)
    {
        $a = $this->makeNewDataWh();
        return view("welcome-due", [
            // 'nodeTreeArray' => json_encode(array_values($taskTree))
        ]);
    }
    
}