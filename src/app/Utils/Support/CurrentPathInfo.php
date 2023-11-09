<?php

namespace App\Utils\Support;

use Illuminate\Support\Str;

class CurrentPathInfo
{
    public static function getTypeReport2($request, $tail = '')
    {
        $pathInfo = $tail ? str_replace($tail, '', $request->getPathInfo()) : $request->getPathInfo();
        // $pathInfo = explode('-', explode('/', trim($pathInfo, '/'))[0]);
        $pathInfo =  [explode('/', trim($pathInfo, '/'))[1]][0];
        $pathInfo =  [explode('-', trim($pathInfo, ' '))[0]];
        $str = strtolower(Str::plural($pathInfo[0]));
        return $str;
    }

    public static function getTypeReport($request, $tail = '')
    {
        $pathInfo = $tail ? str_replace($tail, '', $request->getPathInfo()) : $request->getPathInfo();
        // $pathInfo = explode('-', explode('/', trim($pathInfo, '/'))[0]);
        $pathInfo =  [explode('/', trim($pathInfo, '/'))[0]];
        // dd($pathInfo);
        $str = strtolower(Str::plural($pathInfo[0]));
        if (filter_var($str . 's', FILTER_SANITIZE_NUMBER_INT)) {
            $str = str_replace('_' . filter_var($str . 's', FILTER_SANITIZE_NUMBER_INT), '', $str);
        }
        return $str;
    }

    public static function getEntityReport($request, $tail = '')
    {
        $pathInfo = $tail ? str_replace($tail, '', $request->getPathInfo()) : $request->getPathInfo();
        $pathInfo = explode('-', explode('/', trim($pathInfo, '/'))[1]);
        $str = strtolower(Str::plural($pathInfo[1]));
        if (filter_var($str . 's', FILTER_SANITIZE_NUMBER_INT)) {
            $str = str_replace('_' . filter_var($str . 's', FILTER_SANITIZE_NUMBER_INT), '', $str);
        }
        return $str;
    }

    public static function getViewName($request)
    {
        $pathInfo = $request->getPathInfo();
        $pathInfo = explode('-', explode('/', trim($pathInfo, '/'))[1]);
        return $pathInfo[0];
    }

    public static function getModeKey($request){
        $pathInfo = $request->getPathInfo();
        $pathInfo = explode('-', explode('/', trim($pathInfo, '/'))[1]);
        return $pathInfo[1];
    }
}
