<?php

namespace App\Utils\Support;


class RegexReport
{
    public static function preg_match_all($string, $dataLine)
    {
        preg_match_all('/(?<!\\\)\{\{\s*([^}]*)\s*\}\}/', $string, $matches);
        foreach (last($matches) as $key => $value) {
            $value = trim(str_replace('$', '', $value));
            if (isset($dataLine[$value])) {
                $valueParam =  $dataLine[$value];
                if (is_array($valueParam)) {
                    $itemsIsNumeric = array_filter($valueParam, fn ($item) => is_numeric($item));
                    if (!empty($itemsIsNumeric)) $valueParam = implode(',', $valueParam);
                    else {
                        $str = "";
                        array_walk($valueParam, function ($item) use (&$str) {
                            $str .= "'" . $item . "',";
                        });
                        $valueParam = trim($str, ",");
                    }
                }
                $searchStr = head($matches)[$key];
                $string = str_replace($searchStr, $valueParam, $string);
            } else {
                return dd("Param '{$value}' not found in dataLine array", $string);
            }
        }
        return $string;
    }

    public static function pregLinkRowCell($string)
    {
        preg_match_all('/\$(\w+)/', $string, $matches);
        return $matches;
    }
}
