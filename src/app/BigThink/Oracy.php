<?php

namespace App\BigThink;

class Oracy
{
    static function attach($fnNameWithParenthesis, $collection)
    {
        $fn = substr($fnNameWithParenthesis, 0, strlen($fnNameWithParenthesis) - 2); // Remove the parenthesis
        foreach ($collection as &$item) {
            $item->{$fnNameWithParenthesis} = $item->$fn()->pluck('id');
        }
    }
}
