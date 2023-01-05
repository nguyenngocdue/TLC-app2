<?php

use Illuminate\Support\Arr;

Arr::macro('median', function ($arr, $greater = 1) {
    if ($arr) {
        $arr = array_filter($arr, fn ($item) => $item > $greater);
        $count = count($arr);
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
