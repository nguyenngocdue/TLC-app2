<?php

namespace App\View\Components\Homepage;

use App\Http\Controllers\Workflow\LibNavbars;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;

class MenuProfile extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    private $table = "";
    public function __construct()
    {
        // dump(Route::current());
        $as = CurrentRoute::getControllerAs();
        $this->table = substr($as, 0, strpos($as, "."));
        // dump($this->table);
    }
    private function getUserMenu()
    {
        $navbarStr = json_encode(LibNavbars::getAll());
        switch (env('APP_ENV')) {
            case "production":
                $app = 'app2.tlcmodular.com';
                $redis = '192.168.100.100:15902';
                $phpMyAdmin = "192.168.100.100:18102";
                break;
            case "testing":
                $app = 'beta2.tlcmodular.com';
                $redis = '192.168.100.100:25902';
                $phpMyAdmin = "192.168.100.100:28102";
                break;
            case "local":
            default:
                $app = 'localhost:38002';
                $redis = '127.0.0.1:35902';
                $phpMyAdmin = "localhost:38102";
                break;
        }
        $phpMyAdminLaravel = $phpMyAdmin . "/index.php?db=laravel&route=/database/structure&server=1" . "&";
        $phpMyAdminTable00 = $phpMyAdmin . "/index.php?db=laravel&route=/sql&pos=0&table=" . $this->table . "&"; //<<Last amp & to compromise the slash /

        $navbarStr = str_replace("{app}", $app, $navbarStr);
        $navbarStr = str_replace("{redis}", $redis, $navbarStr);
        $navbarStr = str_replace("{phpMyAdmin}", $phpMyAdminLaravel, $navbarStr);
        $navbarStr = str_replace("{phpMyAdminTable}", $phpMyAdminTable00, $navbarStr);
        // dump($navbarStr);

        $userMenu = json_decode($navbarStr, true);
        $group = [];
        foreach ($userMenu as $value) {
            if (!isset($value['group'])) continue;
            $group[$value['group']][] = $value;
        }
        return $group;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $user = CurrentUser::get();
        $isAdmin = CurrentUser::isAdmin();
        $stopImpersonate = session()->has('impersonate');
        $is3rdParty = CurrentUser::is3rdParty();
        // $rdPartyType = CurrentUser::get3rdPartyType();
        return view('components.homepage.menu-profile', [
            'userMenu' => $this->getUserMenu(),
            'user' => $user,
            'isAdmin' => $isAdmin,
            'is3rdParty' => $is3rdParty,
            'stopImpersonate' => $stopImpersonate,
            // 'rdPartyType' => $rdPartyType,
        ]);
    }
}
