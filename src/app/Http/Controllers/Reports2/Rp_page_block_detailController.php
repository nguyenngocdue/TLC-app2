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

    public function getDataPageBlocks($pageId, $keys)
    {
        $data = Rp_page_block_detail::where('rp_page_id', $pageId)->orderBy('order_no')->get()->toArray();
        return Report::getItemsByKeys($data, $keys);
    }

    public function getData2($reportId)
    {
        return Rp_report::find($reportId)->getPages()->get()->toArray();
    }

    public static $paramsGetData = [
        "getPageBlocksBackground" => ['getData', 8, ['background']],
        "getPageBlocksColSpan" => ['getData', 17, ['col_span']],
        "getPageBlocksOrderNo" => ['getData', 14, ['order_no']],
        "getPagesColSpan" => ['getData2', 17],

    ];


    public function getPagesColSpan()
    {
        $p = static::$paramsGetData[__FUNCTION__];
        $pagesHaveBlocks = $this->{$p[0]}($p[1]);
        foreach ($pagesHaveBlocks as &$page) {
            $page['blocks'] = $this->getDataPageBlocks(
                $page['id'],
                ['id', 'col_span', 'name']
            );
        };
        return $pagesHaveBlocks;
    }

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
