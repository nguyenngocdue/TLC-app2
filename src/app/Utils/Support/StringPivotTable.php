<?php

namespace App\Utils\Support;

use App\Http\Controllers\Workflow\LibPivotTables;
use DateInterval;
use DatePeriod;
use Illuminate\Support\Str;
use DateTime;
use Exception;

class StringPivotTable
{
    public static function stringByPattern($string, $pattern, $indexType = 1)
    {
        preg_match_all($pattern, $string, $matches);
        $result = current($matches[$indexType]);
        if (!$result) return '';
        return $result;
    }
    public static function checkStringContainsChars($string, $chars)
    {
        foreach ($chars as $char) {
            if (strpos($string, $char)) {
                return true;
            }
        }
        return false;
    }
    public static function findCharacterIndex($inputString, $charactersToCheck)
    {
        foreach ($charactersToCheck as $character) {
            $index = strpos($inputString, $character);
            if ($index) {
                return $index;
            }
        }
        return -1;
    }

    private static function separateStrings($strings, $keyNames)
    {
        $output = [];
        foreach ($strings as $value) {
            $pattern = '/[^\w\s]/';
            $result = preg_split($pattern, $value, -1, PREG_SPLIT_NO_EMPTY);
            return $result;
        }
    }

    public static function separateStringsWith2Keys($strings, $keyNames)
    {
        $output = [];
        foreach ($strings as $value) {
            $pattern = '/[^\w\s]/';
            $result = preg_split($pattern, $value, -1, PREG_SPLIT_NO_EMPTY);
            $output[$keyNames[0]][] = $result[0] ?? null;
            $output[$keyNames[1]][] = $result[1] ?? null;
        }
        return $output;
    }

    public static function removeNonAlphabeticCharacters($input)
    {
        $pattern = '/^[^a-zA-Z]+|[^a-zA-Z]+$/';
        return preg_replace($pattern, '', $input);
    }

    public static function extractKeyValuePairs($input)
    {
        $result = [];
        foreach ($input as $item) {
            if (strpos($item, ':') !== false) {
                [$key, $value] = explode(':', $item, 2);
                $result[self::removeNonAlphabeticCharacters($key)] = self::removeNonAlphabeticCharacters($value);
            }
        }
        return $result;
    }
    
    public function getDatesBetween($startDate, $endDate)
    {
        $dateList = array();
        // Convert start and end dates to DateTime objects
        $start = DateTime::createFromFormat('d/m/Y', $startDate);
        $end = DateTime::createFromFormat('d/m/Y', $endDate);
        // Include the end date in the range
        $end = $end->modify('+1 day');
        // Iterate over the range of dates
        $interval = new DateInterval('P1D');
        $dateRange = new DatePeriod($start, $interval, $end);
        foreach ($dateRange as $date) {
            $dateList[] = $date->format('d/m/Y');
        }
        return $dateList;
    }
}
