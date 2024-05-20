<?php

namespace App\Utils;

use App\Models\User_position;
use Brian2694\Toastr\Facades\Toastr;

class ConvertDataUserPosition
{
    public static function handle()
    {
        try {
            $userPositions = User_position::all();
            foreach ($userPositions as $userPosition) {
                $tmp = self::convertData($userPosition->job_desc);
                $tmp2 = self::convertData($userPosition->job_requirement);
                $userPosition->job_desc = $tmp;
                $userPosition->job_requirement = $tmp2;
                $userPosition->save();
            }
            toastr()->success("Convert Data User Position Successfully!", 'Convert Data Successfully');
        } catch (\Throwable $th) {
            toastr()->error($th->getMessage(), 'Convert Data Failed');
        }
    }
    private static function convertData($str)
    {
        $lines = explode("\r\n", $str);
        $processedLines = array_map(function ($line) {
            $trimmedLine = trim($line);
            if (empty($trimmedLine)) return "<p><br data-cke-filler='true'></p>";
            else return "<p> {$trimmedLine} </p>";
        }, $lines);
        return join("", $processedLines);
    }
}
