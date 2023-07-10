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
                $redis = '192.168.100.100:17902';
                $phpMyAdmin = "192.168.100.100:18102";
                break;
            case "testing":
                $app = 'beta2.tlcmodular.com';
                $redis = '192.168.100.100:27902';
                $phpMyAdmin = "192.168.100.100:28102";
                break;
            case "local":
            default:
                $app = 'localhost:38002';
                $redis = '127.0.0.1:37902';
                $phpMyAdmin = "localhost:38102";
                break;
        }
        $phpMyAdminTable = $phpMyAdmin . "/index.php?route=/sql&pos=0&db=laravel&table=" . $this->table . "&"; //<<Last amp & to compromise the slash /

        $navbarStr = str_replace("{app}", $app, $navbarStr);
        $navbarStr = str_replace("{redis}", $redis, $navbarStr);
        $navbarStr = str_replace("{phpMyAdmin}", $phpMyAdmin, $navbarStr);
        $navbarStr = str_replace("{phpMyAdminTable}", $phpMyAdminTable, $navbarStr);
        // dump($navbarStr);

        $userMenu = json_decode($navbarStr, true);
        // dump($userMenu);
        return $userMenu;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $user = CurrentUser::get();

        return view('components.homepage.menu-profile', [
            'userMenu' => $this->getUserMenu(),
            'user' => $user,
        ]);
    }
}
