<?php

namespace App\Http\Controllers\Workflow;

class LibNavbars extends AbstractLib
{
    protected static $key = "navbars";

    public static function getUserMenu()
    {
        $navbarStr = json_encode(LibNavbars::getAll());
        switch (env('APP_ENV')) {
            case "production":
                $app = 'app2.tlcmodular.com';
                $redis = '192.168.100.80:15902';
                $phpMyAdmin = "192.168.100.80:18102";
                break;
            case "testing":
                $app = 'beta2.tlcmodular.com';
                $redis = '192.168.100.80:25902';
                $phpMyAdmin = "192.168.100.80:28102";
                break;
            case "local":
            default:
                $app = 'localhost:38002';
                $redis = '127.0.0.1:35902';
                $phpMyAdmin = "localhost:38102";
                break;
        }
        $phpMyAdminLaravel = $phpMyAdmin . "/index.php?db=laravel&route=/database/structure&server=1" . "&";
        // $phpMyAdminTable00 = $phpMyAdmin . "/index.php?db=laravel&route=/sql&pos=0&table=" . $this->table . "&"; //<<Last amp & to compromise the slash /

        $navbarStr = str_replace("{app}", $app, $navbarStr);
        $navbarStr = str_replace("{redis}", $redis, $navbarStr);
        $navbarStr = str_replace("{phpMyAdmin}", $phpMyAdminLaravel, $navbarStr);
        // $navbarStr = str_replace("{phpMyAdminTable}", $phpMyAdminTable00, $navbarStr);
        // dump($navbarStr);

        $userMenu = json_decode($navbarStr, true);
        $group = [];
        foreach ($userMenu as $value) {
            if (!isset($value['group'])) continue;
            $group[$value['group']][] = $value;
        }
        return $group;
    }
}
