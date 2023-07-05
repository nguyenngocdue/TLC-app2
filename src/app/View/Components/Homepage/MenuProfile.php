<?php

namespace App\View\Components\Homepage;

use App\Utils\CacheToRamForThisSection;
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

    private static function getMenuExpensive()
    {
        $pathFrom = storage_path() . '/json/configs/view/dashboard/navbarUserMenu.json';
        $json = file_get_contents($pathFrom, true);
        return $json;
    }

    private function getUserMenu()
    {
        // $userMenuStr = file_get_contents(storage_path() . '/json/configs/view/dashboard/navbarUserMenu.json');
        $key = "navbarUserMenu_of_the_app";
        $userMenuStr = CacheToRamForThisSection::get($key, fn () => static::getMenuExpensive());
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
        $userMenuStr = str_replace("{{app}}", $app, $userMenuStr);
        $userMenuStr = str_replace("{{redis}}", $redis, $userMenuStr);
        $userMenuStr = str_replace("{{phpMyAdmin}}", $phpMyAdmin, $userMenuStr);
        $userMenuStr = str_replace("{{phpMyAdminTable}}", $phpMyAdminTable, $userMenuStr);
        // dump($userMenuStr);

        $userMenu = json_decode($userMenuStr, true);
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
