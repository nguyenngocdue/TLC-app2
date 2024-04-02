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

    public static function slugifyImageName($fileName, $mediaNames, $extensionsDB = [])
    {
        $extensionFile = substr($fileName, strrpos($fileName, '.') + 1);
        $extensionsDB = $extensionsDB ? $extensionsDB : Attachment::get()->pluck('extension')->toArray();
        $baseName = basename($fileName, '.' . $extensionFile);

        $isValueInData = self::isValueInData($mediaNames, $extensionsDB, $baseName, $extensionFile);
        if (in_array($fileName, $mediaNames) || $isValueInData) {
            // $tempMediaNames = array_column($tempMedia, 'filename');
            $tempData =  $mediaNames;
            [$baseName,  $maxNumber] =  self::getMaxNumberMediaName($tempData, $fileName, $extensionFile);
            $fileName =  $baseName . '-' . $maxNumber + 1 . '.' . $extensionFile;
            if (in_array($fileName, $mediaNames)) {
                Log::info("Duplicate " . $fileName . "in attachments table.");
            }
            return $fileName;
        }
        return $fileName;
    }


    public static function testCasesUploadMedia()
    {
        $extension = 'png';
        $nameUploaded = [
            'file.' . $extension,
            'file-1.' . $extension,
            'file-c-2.' . $extension,
            'file-1-c-2.' . $extension,
            'file-1-2-3-4-5.' . $extension,
            'file-1-2-3-4-5-a.' . $extension,
        ];

        // Assuming this is data represent records in the 'attachments' table
        $namesHaveInDB = [
            ['case_1' => 'file.png'],
            ['case_2' => 'file.png', 'file-1.png'],
            ['case_3' => 'file.png', 'file-2.png', 'file-3.png'],
            ['case_4' => 'file-1.png'],
            ['case_5' => 'file-2.png'],
            ['case_6' => 'file-c-2.png'],
            ['case_7' => 'file-1-c-2.png'],
            ['case_8' => 'file-1-2-3-4-5.png'],
            ['case_9' => 'file-1-2-3-4-5-a.png'],
            ['case_10' => 'file-1-2-3-4-5-a.png', 'file-1-2-3-4-5-a-1.png'],
            ['case_11' => 'file-1-2-3-4-5-a.png', 'file-1-2-3-4-5-a-1.png', 'file-1-2-3-4-5-a-10.png'],
            ['case_12' => 'file-1.png', 'file-2.png', 'file-3.png']
        ];

        $extensionInDB = [
            ['png'],
            ['png', 'png'],
            ['png', 'png', 'png'],
            ['png'],
            ['png'],
            ['png'],
            ['png'],
            ['png'],
            ['png'],
            ['png', 'png'],
            ['png', 'png', 'png'],
            ['png', 'png', 'png']
        ];

        $result = [];
        foreach ($nameUploaded as $nameUp) {
            foreach ($namesHaveInDB as $key => $namesDB) {
                $k = key($namesDB);
                $namesDB = array_values($namesDB);
                $_extensionInDB =  $extensionInDB[$key];
                $newFileName = AttachmentName::slugifyImageName($nameUp, $namesDB, $_extensionInDB);
                $result[$nameUp][$k] = $newFileName;
            }
        }
        dd($result);
    }
}
