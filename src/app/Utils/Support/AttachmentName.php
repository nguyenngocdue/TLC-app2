<?php

namespace App\Utils\Support;

use App\Models\Attachment;
use Illuminate\Support\Facades\Log;

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

    private static function getMaxNumberMediaName($tempData, $fileNameUp, $extension = '')
    {
        $idx = self::indexCharacterInString('.', $fileNameUp);
        $baseName = basename($fileNameUp, '.' . $extension);
        $maxNumber = 0;
        foreach ($tempData as $nameDB) {
            $dot = self::indexCharacterInString('.', $nameDB);
            $n = substr($nameDB, 0, $dot);
            $e = substr($nameDB, $dot + 1, strlen($nameDB) - $dot);
            if ($extension !== $e) continue;

            $hyphenInxFileUp = self::indexCharacterInString('-', $fileNameUp);
            $hyphenInxDB  = self::indexCharacterInString('-', $nameDB);

            $hyphenNameUp = substr($fileNameUp, 0, $hyphenInxFileUp + 1) . '-';

            // update names have tail(-01, -02..) which similar values in data : "hlc-003-1-1"
            if (str_contains($n, $hyphenNameUp) && $hyphenInxFileUp === $hyphenInxDB && $dot === $idx) {
                $num = str_replace($hyphenNameUp, '', $n);
                $maxNumber < $num ? $maxNumber = $num : $maxNumber;
                $baseName = substr($fileNameUp, 0, $hyphenInxFileUp);
            }
            // update names have similar values in data, not tail (-01, -02): "hlc-003-2"
            $aliasName = substr($fileNameUp, 0, $idx) . '-';
            if (str_contains($n, $aliasName)) {
                $num = str_replace($aliasName, '', $n);
                $maxNumber < $num ? $maxNumber = $num : $maxNumber;
            }
        }
        $maxNumber = self::getLastNumberInString($maxNumber);
        // dd("-----------", $fileNameUp, $tempData, $maxNumber);
        return [$baseName, $maxNumber];
    }

    public static function slugifyImageName($file, $tempMedia)
    {
        $fileName =  $file->getClientOriginalName();
        $extensionFile = $file->getClientOriginalExtension();
        $mediaNames = Attachment::get()->pluck('filename')->toArray();

        $extensions = Attachment::get()->pluck('extension')->toArray();
        $baseName = basename($fileName, '.' . $extensionFile);

        /*
            + You have to select a specific case for each corresponding situation. [case 1 <=> case 1]
            + The file extension must match the name of the uploaded file
        */

        /* 
            + names were uploaded 
        */
        //$fileName = 'file.jpg'; //case: 1, 2, 2.1, 2.2, 3
        // $fileName = 'file-1.jpg'; //case: 4, 4.1, 4.2, 5
        // $fileName = 'file-1-1.png'; //case: 4.3
        // $fileName = 'file-c-2.jpg'; //case 6
        // $fileName = 'file-1-c-2.jpg'; //case 7
        // $fileName = 'file-1-2-3-4-5.jpg'; //case 8
        // $fileName = 'file-1-2-3-4-5-a.jpg'; //case: 9, 10, 11

        // $fileName = 'FILE-1.png'; // case: 12



        /* 
            + types of extension that exist in database 
        */
        // $extensionFile = 'jpg';
        // $extensionFile = 'png';

        /*
            + names of media that exist in database 
        */
        // $mediaNames = ['file.jpg']; //case 1 (OK)
        // $mediaNames = ['file.jpg', 'file-1.jpg']; //case 2 (OK)
        // $mediaNames = ['file.jpg', 'file-1.jpg', 'file-10.jpg']; //case 2.1 (OK)
        // $mediaNames = ['file.jpg', 'file-1.jpg', 'file-100.jpg']; //case 2.2 (OK)

        // $mediaNames = ['file.jpg', 'file-1.jpg', 'file-3.jpg']; //case 3 (OK)

        // $mediaNames = ['file-1.jpg', 'file-2.jpg']; //case 4 (OK) 
        // $mediaNames = ['file-1.jpg', 'file-1-1.jpg']; //case 4.1 (OK)
        // $mediaNames = ['file-1.jpg', 'file-1-1.jpg', 'file-1-1.png']; //case 4.2, 4.3 (OK)

        // $mediaNames = ['file-2.jpg']; //case 5 => current out = file.jpg (NO)
        // $mediaNames = ['file-c-2.jpg']; //case 6 (OK)
        // $mediaNames = ['file-1-c-2.jpg']; //case 7 (OK)
        // $mediaNames = ['file-1-2-3-4-5.jpg']; //case 8 (OK)
        // $mediaNames = ['file-1-2-3-4-5-a.jpg']; //case 9 (OK)
        // $mediaNames = ['file-1-2-3-4-5-a-1.jpg', 'file-1-2-3-4-5-a.jpg']; //case 10 (OK)
        // $mediaNames = ['file-1-2-3-4-5-a-1.jpg', 'file-1-2-3-4-5-a.jpg', 'file-1-2-3-4-5-a-10.jpg']; //case 11 (OK)

        // $mediaNames = ['FILE.png', 'FILE-1.png', 'FILE-1-1.png', 'FILE-1-2.png', 'FILE-1-3.png']; //case 12 (OK)

        $isValueInData = self::isValueInData($mediaNames, $extensions, $baseName, $extensionFile);

        if (in_array($fileName, $mediaNames) || $isValueInData) {
            $tempMediaNames = array_column($tempMedia, 'filename');
            $tempData =  array_merge($mediaNames, $tempMediaNames);
            [$baseName,  $maxNumber] =  self::getMaxNumberMediaName($tempData, $fileName, $extensionFile);
            // dump($baseName,  $maxNumber);
            $fileName =  $baseName . '-' . $maxNumber + 1 . '.' . $extensionFile;
            if (in_array($fileName, $mediaNames)) {
                Log::info("Duplicate " . $fileName . "in attachments table.");
            }
            return $fileName;
        }
        return $fileName;
    }
}
