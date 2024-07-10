<?php

namespace App\Http\Controllers\Reports2;

use App\Http\Controllers\Controller;
use App\Models\Rp_block;
use App\Models\Rp_page_block_detail;
use App\Models\Rp_report;
use App\Utils\Support\Report;

class Rp_page_block_detailController extends Controller
{

    public function getData($reportId, $keys)
    {
        $pageIds = Rp_report::find($reportId)->getPages()->pluck('id')->toArray();
        $data = Rp_page_block_detail::whereIn('rp_page_id', $pageIds)->get()->toArray();
        return Report::getItemsByKeys($data, $keys);
    }

    public static $paramsGetData = [
        "getPageBlocksBackground" => ['getData', 8, ['col_span']],
        "getPageBlocksColSpan" => ['getData', 17, ['background']],
        "getPageBlocksOrderNo" => ['getData', 14, ['order_no']],
    ];

    public function getPageBlocksColSpan()
    {
        $p = static::$paramsGetData[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getPageBlocksBackground()
    {
        $p = static::$paramsGetData[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getPageBlocksOrderNo()
    {
        $p = static::$paramsGetData[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
