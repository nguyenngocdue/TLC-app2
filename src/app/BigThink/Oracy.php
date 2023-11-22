<?php

namespace App\BigThink;

use Illuminate\Support\Arr;

class Oracy
{
    static function attach($fnNameWithParenthesis, $collection, $toArray = false)
    {
        $fn = substr($fnNameWithParenthesis, 0, strlen($fnNameWithParenthesis) - 2); // Remove the parenthesis
        // if (!Arr::isTraversable($collection)) $collection = [$collection];
        foreach ($collection as &$item) {
            if ($item) {
                if (method_exists($item, $fn)) {
                    $values = $toArray ? $item->$fn()->pluck('id')->toArray() : $item->$fn()->pluck('id');
                    $item->{$fnNameWithParenthesis} = $values;
                    // } else {
                    //     dump($fn . " is not found, oracy attachment is skipped.");
                }
            }
        }
    }
}
