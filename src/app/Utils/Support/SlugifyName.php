<?php

namespace App\Utils\Support;

use App\Models\Attachment;

class SlugifyName
{

    private static  function indexCharacterInString($strSearch, $str, $ofs = 0)
    {
        while (($pos = strpos($str, $strSearch, $ofs)) !== false) {
            $ofs = $pos + 1;
        }
        return ($ofs - 1) >= 0 ? ($ofs - 1) : null;
    }

    // public static function slugNameToBeSaved($name, $slugList)
    // {
    //     // $name = "hlc-500-20";
    //     if (in_array($name, $slugList)) {
    //         $maxNumber = 0;
    //         foreach ($slugList as $key => $value) {
    //             if (!strpos($value, $name) && str_contains($value, $name) && str_contains($value, $name . '-')) {
    //                 $tailNumber = str_replace($name . '-', '', $value);
    //                 if (is_numeric($tailNumber) && $tailNumber * 1 > $maxNumber) {
    //                     $maxNumber = $tailNumber;
    //                     $name = $name . '-' . $maxNumber + 1;
    //                 }
    //             }
    //             if (!str_contains($value, $name . '-')) {
    //                 $titleName =  substr($name, 0, self::indexCharacterInString('-', $name) + 1);
    //                 $number = substr($name, self::indexCharacterInString('-', $name) + 1, strlen($name)) * 1;
    //                 if ($number * 1 > $maxNumber) {
    //                     // dump($maxNumber, $number);
    //                     $maxNumber = $number;
    //                     $name = $titleName . $maxNumber + 1;
    //                 }
    //             }
    //         }
    //     }
    //     return $name;
    // }

    public static function slugNameToBeSaved($name, $slugList)
    {
        // $name = "hlc-500-63";
        if (in_array($name, $slugList)) {
            $maxNumber = 0;
            foreach ($slugList as $key => $value) {
                if (!strpos($value, $name) && str_contains($value, $name)) {
                    $tailNumber = str_replace($name . '-', '', $value);
                    // dump($tailNumber);
                    if (is_numeric($tailNumber) && $tailNumber * 1 > $maxNumber) {
                        $maxNumber = $tailNumber;
                    }
                }
            }
            $name = $name . '-' . $maxNumber + 1;
        }
        return $name;
    }
}
