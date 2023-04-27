<?php

namespace App\View\Components\Formula;

use App\Utils\Support\SlugifyName;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class All_SlugifyByName
{
    private function getListOfSlugFromDB($type, $id)
    {
        $table = DB::table(Str::plural($type));
        $collection = $table->where([['id', '!=', $id]])->select('id', 'slug')->get();
        $json = json_decode($collection, true);
        $valSlugDB = array_column($json,  'slug', 'id');
        return $valSlugDB;
    }

    public function __invoke($value, $type, $id)
    {
        $value = Str::slug($value);
        $slugList = $this->getListOfSlugFromDB($type, $id);
        $result = SlugifyName::slugNameToBeSaved($value, $slugList);
        return $result;
    }
}
