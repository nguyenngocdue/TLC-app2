<?php

namespace App\View\Components\Formula;

use App\Helpers\Helper;
use App\Http\Controllers\Workflow\LibApps;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class All_DocId
{
    public function __invoke($item, $type)
    {
        $nameColumnDocIDFormat = self::getAllEntityHasDocId($type);
        if (!$nameColumnDocIDFormat) {
            toastr()->warning('Please set value "Doc Id Format Column" in ManageApps', 'Warning Settings Formula');
        }
        $tableName = Str::plural($type);
        $maxDocID = DB::table($tableName)->where($nameColumnDocIDFormat, $item[$nameColumnDocIDFormat])->max('doc_id');
        $result = $maxDocID + 1;
        return $result;
    }

    public static function getAllEntityHasDocId($type)
    {
        $libAppsData = LibApps::getFor($type);
        $nameColumnDocIDFormat = $libAppsData['doc_id_format_column'];
        return $nameColumnDocIDFormat;
    }
}
