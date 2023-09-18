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
        return ($ofs - 1) >= 0 ? ($ofs - 1) : null;
    }

    private static function getLastNumberInString($string)
    {
        // $string = "abc123def456ghi789";
        $matches = [];

        if (preg_match_all('/\d+/', $string, $matches)) {
            $lastNumber = end($matches[0]);
            return $lastNumber;
        } else {
            return 000;
        }
    }

    private static function getMaxNumberMediaName($tempData, $fileName, $extension = '')
    {
        $idx = self::indexCharacterInString('.', $fileName);
        $baseName = basename($fileName, '.' . $extension);
        $maxNumber = 0;
        foreach ($tempData as $value) {
            $dot = self::indexCharacterInString('.', $value);
            $n = substr($value, 0, $dot);
            $e = substr($value, $dot + 1, strlen($value) - $dot);
            if ($extension !== $e) continue;

            $hyphenInxFile = self::indexCharacterInString('-', $fileName);
            $hyphenInx  = self::indexCharacterInString('-', $value);
            $hyphenName = substr($fileName, 0, $hyphenInxFile) . '-';
            // update names have tail(-01, -02..) which similar values in data : "hlc-003-1-1"
            if (str_contains($n, $hyphenName) && $hyphenInxFile === $hyphenInx && $dot === $idx) {
                $num = str_replace($hyphenName, '', $n);
                $maxNumber < $num ? $maxNumber = $num : $maxNumber;
                $baseName = substr($fileName, 0, $hyphenInxFile);
            }
            // update names have similar values in data, not tail (-01, -02): "hlc-003-2"
            $aliasName = substr($fileName, 0, $idx) . '-';
            if (str_contains($n, $aliasName)) {
                $num = str_replace($aliasName, '', $n);
                $maxNumber < $num ? $maxNumber = $num : $maxNumber;
            }
            // update names have tail similar a value in data
            if ($fileName === $value) {
                $maxNumber = 0;
                $baseName = $n;
            }
        }
        $maxNumber = self::getLastNumberInString($maxNumber);
        // dd("-----------", $fileName, $tempData, $maxNumber);
        return [$baseName, $maxNumber];
    }

    public static function slugifyImageName($file, $tempMedia)
    {
        $fileName =  $file->getClientOriginalName();
        $extensionFile = $file->getClientOriginalExtension();
        $mediaNames = Attachment::get()->pluck('filename')->toArray();
        $extensions = Attachment::get()->pluck('extension')->toArray();
        $baseName = basename($fileName, '.' . $extensionFile);

        $isValueInData = self::isValueInData($mediaNames, $extensions, $baseName, $extensionFile);
        if (in_array($fileName, $mediaNames) || $isValueInData) {
            $tempMediaNames = array_column($tempMedia, 'filename');
            $tempData =  array_merge($mediaNames, $tempMediaNames);
            [$baseName,  $maxNumber] =  self::getMaxNumberMediaName($tempData, $fileName, $extensionFile);
            // dump($maxNumber);
            $fileName =  $baseName . '-' . $maxNumber + 1 . '.' . $extensionFile;
            return $fileName;
        }
        return $fileName;
    }
}
