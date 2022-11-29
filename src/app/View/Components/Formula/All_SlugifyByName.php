<?php

namespace App\View\Components\Formula;

use App\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class All_SlugifyByName
{
    public static function All_SlugifyByName($type, $itemDB)
    {

        $newValueArray  = [];
        $valSlugDB = array_column(json_decode(DB::table(Str::plural($type))->where([['id', '!=', $itemDB['id']]])->select('id', 'slug')->get(), true),  'slug', 'id');
        $content = is_null($itemDB['slug']) ? Str::slug($itemDB['name']) : Str::slug($itemDB['slug']);
        $newValueArray = Helper::slugNameToSaveDB($content, $valSlugDB);
        return $newValueArray;
    }
}
