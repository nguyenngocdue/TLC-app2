<?php

namespace App\Utils\Support;

use App\Http\Controllers\Workflow\LibPivotTables;
use Illuminate\Support\Str;
use DateTime;
use Exception;

class StringPivotTable
{
    public static function stringByPattern($string, $pattern, $indexType=1) {
        preg_match_all($pattern, $string, $matches);
        $result = current($matches[$indexType]);
        if (!$result) return '';
        return $result;
    }
    public static function checkStringContainsChars($string, $chars) {
        foreach ($chars as $char) {
            if (strpos($string, $char)) {
                return true;
            }
        }
        return false;
    }
    public static function findCharacterIndex($inputString, $charactersToCheck) {
        foreach ($charactersToCheck as $character) {
            $index = strpos($inputString, $character);
            if ($index) {
                return $index;
            }
        }
        return -1;
    }
    
    
}
