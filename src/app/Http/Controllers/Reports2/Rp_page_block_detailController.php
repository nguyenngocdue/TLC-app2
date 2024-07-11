<?php

namespace App\Http\Controllers\Reports2;

use App\Http\Controllers\Controller;
use App\Models\Rp_block;
use App\Models\Rp_page_block_detail;
use App\Models\Rp_report;
use App\Utils\Support\Report;

class Rp_page_block_detailController extends Controller
{

    public function getDataPageBlocks($pageId, $keys)
    {
        $data = Rp_page_block_detail::where('rp_page_id', $pageId)
            ->orderBy('order_no')
            ->get()
            ->toArray();
        foreach ($data as $key => &$value) {
            $blocks = Rp_page_block_detail::find($value['id'])
                ->getBlock()
                ->get()
                ->first()
                ->toArray();
            $value = array_merge($value, $blocks);
        }
        // dd($data);
        return Report::getItemsByKeys($data, $keys);
    }

    private function mapBlocksIntoPages($pages)
    {
        foreach ($pages as &$page) {
            $page['blocks'] = $this->getDataPageBlocks(
                $page['id'],
                ['id', 'col_span', 'name', 'renderer_type', 'order_no']
            );
        };
        return $pages;
    }

    public function getData($reportId)
    {
        return Rp_report::find($reportId)->getPages()->get()->toArray();
    }


    public static $paramsGetData = [
        "getPageBlocksBackground" => ['getData', 8],
        "getPageBlocksOrderNo" => ['getData', 14],
        "getPageBlocksColSpan" => ['getData', 17],

    ];


    public function getPageBlocksColSpan()
    {
        $p = static::$paramsGetData[__FUNCTION__];
        $pages = $this->{$p[0]}($p[1]);
        $pagesHaveBlocks = $this->mapBlocksIntoPages($pages);
        return $pagesHaveBlocks;
    }


    public function getPageBlocksBackground()
    {
        $p = static::$paramsGetData[__FUNCTION__];
        $pages = $this->{$p[0]}($p[1]);
        $pageBlockMap = $this->mapBlocksIntoPages($pages);
        return $pageBlockMap;
    }

    public function getPageBlocksOrderNo()
    {
        $p = static::$paramsGetData[__FUNCTION__];
        $pages = $this->{$p[0]}($p[1]);
        $pageBlockMap = $this->mapBlocksIntoPages($pages);
        return $pageBlockMap;
    }
}
