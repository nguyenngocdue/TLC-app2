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
        "getPageLandscape" => ['getData', 1, ['id', 'name', 'report_id', 'order_no', 'is_landscape']],
        "getPagePortrait" => ['getData', 2, ['is_landscape']],
        "getPageWidthAndHeight" => ['getData', 3, ['width', 'height']],
        "getPageFullWidth" => ['getData', 4, ['width', 'height', 'is_full_width']],
        "getPageOrderNo" => ['getData', 5, ['id', 'name', 'report_id', 'order_no']],
        "getPageHeadFooterBackground" => ['getData', 6, ['id', 'letter_head_id', 'letter_footer_id']],
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

    public function getPageHeadFooterBackground()
    {
        $p = static::$paramsGetData[__FUNCTION__];
        $data = $this->{$p[0]}($p[1], $p[2]);
        foreach ($data as &$value) {
            $attachments = Rp_page::find($value['id'])
                ->attachment_background()
                ->get()
                ->first();
            $attachments = $attachments ? $attachments->toArray() : [];
            $value['url_thumbnail'] = '';
            $value['url_media'] = '';
            if ($attachments) {
                $value['url_thumbnail'] =  "'" . env('AWS_ENDPOINT') . '/tlc-app//' . $attachments['url_thumbnail'] . "'";
                $value['url_media'] = "'" . env('AWS_ENDPOINT') . '/tlc-app//' . $attachments['url_media'] . "'";
            }
        }
        return  $data;
    }

    public function getPageBlocksColSpan()
    {
        $p = static::$paramsGetData[__FUNCTION__];
        return $this->{$p[0]}($p[1], $p[2]);
    }
}
