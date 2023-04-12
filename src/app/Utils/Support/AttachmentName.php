<?php

namespace App\Utils\Support;

use App\Models\Attachment;

class AttachmentName
{
    private static function isValueInData($needle, $hayStack, $strSearch, $extension)
    {
        foreach ($needle as $key => $value) {
            if (str_contains($value, $strSearch) && str_contains($hayStack[$key], $extension)) {
                return true;
            }
        }
        return false;
    }

    private static  function indexCharacterInString($strSearch, $str, $ofs = 0)
    {
        while (($pos = strpos($str, $strSearch, $ofs)) !== false) {
            $ofs = $pos + 1;
        }
        return $ofs - 1;
    }



    private static function getMaxNumberMediaName($tempData, $fileName, $extension = '')
    {
        $idx = self::indexCharacterInString('.', $fileName);
        // $idx5 = self::indexCharacterInString('-', "hlc-003-1.jpg");
        // dd($idx5);
        $baseName = basename($fileName, '.' . $extension);
        // dd($fileName, $tempData);
        $maxNumber = 0;
        foreach ($tempData as $value) {
            $dot = self::indexCharacterInString('.', $value);
            $n = substr($value, 0, $dot);
            $e = substr($value, $dot + 1, strlen($value) - $dot);
            $aliasName = substr($fileName, 0, $idx) . '-';

            // update names have tail(-01, -02..) similar values in data
            $hyphenInxFile = self::indexCharacterInString('-', $fileName);
            $hyphenInx  = self::indexCharacterInString('-', $value);
            $n2 = substr($fileName, 0, $hyphenInxFile) . '-';
            $numberInStr = substr($value, $hyphenInx + 1, $dot - $hyphenInx - 1);
            // dump($numberInStr);




            if (is_numeric($numberInStr) &&  str_contains($n, $n2) && $extension === $e && $hyphenInxFile === $hyphenInx && $dot === $idx) {
                // dd($numberInStr);
                $maxNumber < $numberInStr ? $maxNumber = $numberInStr : $maxNumber;
                $baseName = substr($fileName, 0, $hyphenInxFile);
            }

            // update names have similar values in data not tail
            if (!is_numeric($numberInStr) && str_contains($n, $aliasName) && $extension === $e) {
                $num = str_replace($aliasName, '', $n);
                $maxNumber < $num ? $maxNumber = $num : $maxNumber;
                $num = str_replace($aliasName, '', $n);
                // dump($n, $num, $maxNumber);
            }

            // update names have tail similar a value in data
            if ($fileName === $value && !is_numeric($numberInStr)) {
                $maxNumber = 0;
                $baseName = $n;
                // dd($n);
            }
        }
        // dd("-----------", $fileName, $tempData, $maxNumber);
        return [$baseName, $maxNumber];
    }

    public static function customizeSlugData($file, $mediaTemp)
    {
        $fileName =  $file->getClientOriginalName();
        $extensionFile = $file->getClientOriginalExtension();
        $baseName = basename($fileName, '.' . $extensionFile);

        $mediaNames = Attachment::get()->pluck('filename')->toArray();
        $extensions = Attachment::get()->pluck('extension')->toArray();
        $isValueInData = self::isValueInData($mediaNames, $extensions, $baseName, $extensionFile);

        if (in_array($fileName, $mediaNames) || $isValueInData) {
            $tempMediaNames = array_column($mediaTemp, 'filename');
            $tempData =  array_merge($mediaNames, $tempMediaNames);
            [$baseName,  $maxNumber] =  self::getMaxNumberMediaName($tempData, $fileName, $extensionFile);
            $fileName =  $baseName . '-' . $maxNumber + 1 . '.' . $extensionFile;
            return $fileName;
        }
        return $fileName;
    }
}
