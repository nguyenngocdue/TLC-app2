<?php

namespace App\Http\Controllers\Reports2;

use App\Http\Controllers\Controller;
use App\Models\Rp_page;
use App\Utils\Support\Report;

class Rp_pageController extends Controller
{

    public function getData($reportId, $keys)
    {
        $rpPage = new Rp_page();
        $data = $rpPage->where('report_id', $reportId)->get()->toArray();
        return Report::getItemsByKeys($data, $keys);
    }

    public static $paramsGetData = [
        // function name => method name, report_id, key to get value
        "getPageLandscape" => ['getData', 1, ['is_landscape']],
        "getPagePortrait" => ['getData', 2, ['is_landscape']],
        "getPageWidthAndHeight" => ['getData', 3, ['width', 'height']],
        "getPageFullWidth" => ['getData', 4, ['is_full_width']],
        "getPageOrderNo" => ['getData', 5, ['order_no']],
        "getPageHeaderFooterBackground" => ['getData', 6, ['letter_head_id', 'letter_footer_id', 'background']],
        "getPageLetterHeadStackable" => ['getData', 13, ['is_stackable_letter_head']],

    ];

    public function getPageLandscape()
    {
        $p = static::$paramsGetData[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getPagePortrait()
    {
        $p = static::$paramsGetData[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getPageWidthAndHeight()
    {
        $p = static::$paramsGetData[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getPageFullWidth()
    {
        $p = static::$paramsGetData[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getPageOrderNo()
    {
        $p = static::$paramsGetData[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getPageLetterHeadStackable()
    {
        $p = static::$paramsGetData[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getPageHeaderFooterBackground()
    {
        $p = static::$paramsGetData[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }

    public function getPageBlocksColSpan()
    {
        $p = static::$paramsGetData[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
