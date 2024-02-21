<?php

namespace App\Utils;

use App\Models\User_position;

class ConvertDataUserPosition
{
    public static function handle(){
        $userPositions = User_position::all();
        foreach ($userPositions as $userPosition) {
            $tmp = preg_replace('/\r\n\r\n/',"\r\n<p><br data-cke-filler='true'></p>\r\n",$userPosition->job_desc);
            $tmp2 = preg_replace('/\r\n\r\n/',"\r\n<p><br data-cke-filler='true'></p>\r\n",$userPosition->job_requirement);
            $userPosition->job_desc = $tmp;
            $userPosition->job_requirement = $tmp2;
            $userPosition->save();
        }
    }
    
}
