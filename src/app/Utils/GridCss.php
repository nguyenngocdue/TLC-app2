<?php
namespace App\Utils;
class GridCss{
    public static function getGridCss($count)
    {
        $gridCss = '';
        switch ($count) {
            case 0:
            case 1:
                $gridCss = '';
                break;
            case 2:
                $gridCss = 'grid-cols-1 sm:grid-cols-2 gap-10';
                break;
            case 3:
                $gridCss = 'grid-cols-1 sm:grid-cols-2 gap-5';
                break;
            case 4:
                $gridCss = 'grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5';
                break;
            case 5:
                $gridCss = 'grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-5';
                break;
            case 6:
                $gridCss = 'grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-5';
                break;
            case 7:
                $gridCss = 'grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-5';
                break;
            case 8:
            case 9:
            case 10:
            case 11:
            case 12:
            default:
                $gridCss = 'grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6 2xl:grid-cols-8 gap-5';
                break;
        }
        return $gridCss;
    }
}