<?php

namespace App\Utils\Support;

use App\Models\Attachment;
use Illuminate\Support\Facades\Log;

class AttachmentName
{
    private static function isValueInData($needle, $hayStack, $strSearch, $extension)
    {
        foreach ($needle as $key => $value) {
            if (in_array($needle, [$strSearch]) && str_contains($value, $strSearch) && str_contains($hayStack[$key], $extension)) {
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
            $nameDb = substr($nameDB, 0, $dot);
            $e = substr($nameDB, $dot + 1, strlen($nameDB) - $dot);
            if ($extension !== $e) continue;

            $hyphenInxFileUp = self::indexCharacterInString('-', $fileNameUp);
            $hyphenInxDB  = self::indexCharacterInString('-', $nameDB);

            $hyphenNameUp = substr($fileNameUp, 0, $hyphenInxFileUp + 1) . '-';

            // check case: update names have tail(-01, -02..) which similar values in data : "hlc-003-1-1"
            if (str_contains($nameDb, $hyphenNameUp) && $hyphenInxFileUp === $hyphenInxDB && $dot === $idx) {
                $num = str_replace($hyphenNameUp, '', $nameDb);
                $maxNumber < $num ? $maxNumber = $num : $maxNumber;
                $baseName = substr($fileNameUp, 0, $hyphenInxFileUp);
            }

            // // check case:  "11.png" is contained within "screenshot-20-11-2025.png"
            // $aliasName = substr($fileNameUp, 0, $idx);
            // if ($nameDb === $aliasName) {
            //     $num = str_replace($aliasName, '', $nameDb);
            //     $maxNumber < $num ? $maxNumber = $num : $maxNumber;
            // }

            // // check case: update names have similar values in data, not tail (-01, -02): "hlc-003-2"
            // $aliasName = substr($fileNameUp, 0, $idx) . '-';
            // if (str_contains($nameDb, $aliasName)) {
            //     // dd($nameDb, $aliasName);
            //     $num = str_replace($aliasName, '', $nameDb);
            //     Log::info($nameDb);
            //     $maxNumber < $num ? $maxNumber = $num : $maxNumber;
            // }

            ///refactor
            $aliasName1 = substr($fileNameUp, 0, $idx);
            $aliasName2 = $aliasName1 . '-';

            if ($nameDb === $aliasName1 || str_contains($nameDb, $aliasName2)) {
                $num = str_replace([$aliasName1, $aliasName2], '', $nameDb);
                $maxNumber = max($maxNumber, $num);
            }
        }
        $maxNumber = self::getLastNumberInString($maxNumber);
        // dd("-----------", $fileNameUp, $maxNumber);
        return [$baseName, $maxNumber];
    }

    public static function slugifyImageName($fileName, $mediaNames, $extensionsDB = [])
    {
        $extensionFile = substr($fileName, strrpos($fileName, '.') + 1);
        $extensionsDB = $extensionsDB ? $extensionsDB : Attachment::get()->pluck('extension')->toArray();
        $baseName = basename($fileName, '.' . $extensionFile);

        $isValueInData = self::isValueInData($mediaNames, $extensionsDB, $baseName, $extensionFile);
        // dump($isValueInData, $extensionsDB, $baseName, $mediaNames);

        if (in_array($fileName, $mediaNames) || $isValueInData) {
            // $tempMediaNames = array_column($tempMedia, 'filename');
            $tempData =  $mediaNames;
            [$baseName,  $maxNumber] =  self::getMaxNumberMediaName($tempData, $fileName, $extensionFile);
            $index = 1;
            do {
                $fileName =  $baseName . '-' . $maxNumber + $index . '.' . $extensionFile;
                $index++;
                // if (in_array($fileName, $mediaNames)) {
                //     Log::info("Duplicate " . $fileName . " in attachments table.");
                // }
            } while (in_array($fileName, $mediaNames));
            // Log::info($fileName);
            // dd($fileName);
            return $fileName;
        }
        return $fileName;
    }

    private static function extractExtensions($fileNames)
    {
        return array_map(function ($fileName) {
            return pathinfo($fileName, PATHINFO_EXTENSION);
        }, $fileNames);
    }
    public static function testCasesUploadMedia()
    {
        $testCases = [
            [
                'haystack' =>  ['file.png'],
                'needle' => 'file.png',
                'expect' => 'file-1.png',
            ],
            [
                'haystack' => ['file.png', 'file-1.png'],
                'needle' => 'file.png',
                'expect' => 'file-2.png',
            ],
            [
                'haystack' => ['file.png', 'file-2.png', 'file-3.png'],
                'needle' => 'file.png',
                'expect' => 'file-4.png',
            ],
            [
                'haystack' => ['file-1.png'],
                'needle' => 'file.png',
                'expect' => 'file.png',
            ],
            [
                'haystack' =>   ['file.png', 'file-1.png'],
                'needle' => 'file.png',
                'expect' => 'file-2.png',
            ],
            [
                'haystack' => ['file.png', 'file-1.png'],
                'needle' => 'file-1.png',
                'expect' => 'file-1-1.png',
            ],
            [
                'haystack' =>  ['file-c-2.png'],
                'needle' => 'file-c-2.png',
                'expect' => 'file-c-2-1.png',
            ],
            [
                'haystack' =>  ['file-c-2-1.png'],
                'needle' => 'file-c-2.png',
                'expect' => 'file-c-2.png',
            ],
            [
                'haystack' =>  ['file-c-2-1.png', 'file-c-2-10.png'],
                'needle' => 'file-c-2.png',
                'expect' => 'file-c-2.png',
            ],
            [
                'haystack' =>  ['file-c-2.png', 'file-c-2-10.png'],
                'needle' => 'file-c-2.png',
                'expect' => 'file-c-2-11.png',
            ],
            [
                'haystack' =>  ['file-c-1-2-3-4a.png'],
                'needle' => 'file-c-1-2-3-4a.png',
                'expect' => 'file-c-1-2-3-4a-1.png',
            ],
            [
                'haystack' =>  ['file-c-1-2-3-4a.png', 'file-c-1-2-3-4a-1.png'],
                'needle' => 'file-c-1-2-3-4a.png',
                'expect' => 'file-c-1-2-3-4a-2.png',
            ]
        ];


        $result = [];
        foreach ($testCases as $key => $cases) {
            $haystack = $cases['haystack'];
            $needle = $cases['needle'];
            $expect = $cases['expect'];
            $extensionDB = AttachmentName::extractExtensions($haystack);
            $newFileName = AttachmentName::slugifyImageName($needle, $haystack, $extensionDB);
            if ($expect === $newFileName) {
                $result['key=' . $key + 1 . ': ' . $needle] = 'PASS';
            } else {
                $result['key=' . $key + 1 . ': ' . $needle] = 'FAILED' . ' (expect: ' . $expect . ' !== output:' . $newFileName . ')';
            }
        }
        dd($result);
    }
}
