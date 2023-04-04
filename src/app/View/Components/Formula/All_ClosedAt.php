<?php

namespace App\View\Components\Formula;

use App\Helpers\Helper;
use App\Http\Controllers\Workflow\LibApps;
use App\Utils\Support\Json\Definitions;
use App\Utils\Support\Json\SuperProps;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class All_ClosedAt
{
    public function __invoke($item, $type)
    {
        $result = null;
        $definitionClosed = Definitions::getAllOf($type)['closed'] ?? [];
        $closed = array_filter($definitionClosed, fn ($item) => $item == "true");
        foreach ($closed as $key => $value) {
            if ($item['status'] == $key) {
                $result =  Carbon::now()->toDateTimeString();
            }
        }
        return $result;
    }
}
