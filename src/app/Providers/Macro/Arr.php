<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

Arr::macro('groupByToChildren', function ($arr, $col1, $col2 = null) {
    $result = [];
    foreach ($arr as $key => $item) {
        if (is_null($col2)) {
            $result[$item[$col1]]['title'] =  Str::appTitle($item[$col1]);
            $result[$item[$col1]]["children"][$key] = $item;
        } else {
            $result[$item[$col1]]['title'] =  Str::appTitle($item[$col1]);
            $result[$item[$col1]]["children"][$item[$col2]]["title"] =  Str::appTitle($item[$col2]);
            $item['title'] = Str::appTitle($item['title']);
            $result[$item[$col1]]["children"][$item[$col2]]["children"][$key] = $item;
        }
    }
    return $result;
});
Arr::macro('groupBy', function ($arr, $col1, $col2 = null) {
    $result = [];
    foreach ($arr as $key => $item) {
        if (is_null($col2)) {
            $result[$item[$col1]][$key] = $item;
        } else {
            $result[$item[$col1]][$item[$col2]][$key] = $item;
        }
    }
    return $result;
});
Arr::macro('median', function ($arr, $greater = 1) {
    if ($arr) {
        if (sizeof($arr) == 0) return 0;
        if (sizeof($arr) == 1) return $arr[0];
        $arr = array_filter($arr, fn ($item) => $item > $greater);
        $count = count($arr);
        if ($count == 0) return 0;
        sort($arr);
        $mid = floor(($count - 1) / 2);
        return ($arr[$mid] + $arr[$mid + 1 - $count % 2]) / 2;
    }
    return 0;
});
Arr::macro('toAssoc', function (array $array) {
    // [1, 2, 3] => ['1' => 1, '2' => 2, '3' => 3]
    // [1 => ['a' => 9], 2, 3] => ['1' => ['a' => 9], '2' => 2, '3' => 3]
    // [1, 2 => ['a' => 9], 3] => ['1' => 1, '2' => ['a' => 9], '3' => 3]
    $result = [];
    foreach ($array as $key => $item) {
        if (is_array($item)) $result[$key] = $item;
        else $result[$item] = $item;
    }
    return $result;
});
Arr::macro('moveTo', function ($json, $index, $newIndex) {
    $value = $json[$index];
    unset($json[$index]);
    array_splice($json, $newIndex, 0, [$value]);
    return $json;
});
Arr::macro('moveDirection', function ($json, $direction, $index, $name = null) {
    switch ($direction) {
        case "up":
            if ($index === 0) {
                $value = $json[0];
                unset($json[0]);
                array_push($json, $value);
            } else {
                $tmp = $json[$index - 1];
                $json[$index - 1] = $json[$index];
                $json[$index] = $tmp;
            }
            break;
        case "down":
            if ($index === sizeof($json) - 1) {
                $value = array_pop($json);
                array_unshift($json, $value);
            } else {
                $tmp = $json[$index + 1];
                $json[$index + 1] = $json[$index];
                $json[$index] = $tmp;
            }
            break;
        case "left":
            if (!is_null($name)) {
                array_push($json, $name);
            }
            break;
        case "right":
            if (!is_null($name)) {
                $json = array_filter($json, fn ($name0) => $name !== $name0);
            }
            break;
        case "right_by_name":
            if (!is_null($name)) {
                $json = array_filter($json, fn ($item) => $name !== $item['name']);
            }
            break;
    }
    return $json;
});
Arr::macro('containsEach', function ($arrayCheck, $arrayIndex) {
    foreach ($arrayCheck as $value) {
        if (in_array($value, $arrayIndex)) {
            return true;
        }
    }
    return false;
});
Arr::macro('normalizeSelected', function ($selected, $old = null) {
    if ($old) {
        return (is_array($old)) ? "[" . join(",", $old) . "]" : "[$old]";
    } else {
        if (is_array($selected)) {
            return "[" . join(",", $selected) . "]";
        } elseif (is_string($selected) || is_numeric($selected)) {
            if (str_starts_with($selected, "[")) return $selected;
            else return "[" . $selected . "]";
        }

        if (is_null($selected)) return "[]";
        dump("Unknown how to normalize selected [$selected]");
        // if (isset($selected[0])) {
        //     $selected =  ($selected[0] != '[') ? "[" . $selected . "]" : $selected;
        // } else {
        //     $selected = "[]";
        // }
    }
    // return $selected;
});
Arr::macro('allElementsAre', function ($array, $x) {
    foreach ($array as $value) if ($value !== $x) return false;
    return true;
});
Arr::macro('sameContent', function (array $array1, array $array2) {
    return (empty(array_diff($array1, $array2)) && empty(array_diff($array2, $array1)));
});
