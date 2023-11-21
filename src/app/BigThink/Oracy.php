<?php

namespace App\BigThink;

use Illuminate\Support\Arr;

class Oracy
{
    static function attach($fnNameWithParenthesis, $item_or_collection, $toArray = false)
    {
        $fn = substr($fnNameWithParenthesis, 0, strlen($fnNameWithParenthesis) - 2); // Remove the parenthesis
        if (!Arr::isTraversable($item_or_collection)) $item_or_collection = [$item_or_collection];
        foreach ($item_or_collection as &$item) {
            if ($item) {
                $values = $toArray ? $item->$fn()->pluck('id')->toArray() : $item->$fn()->pluck('id');
                $item->{$fnNameWithParenthesis} = $values;
            }
        }
    }
}
