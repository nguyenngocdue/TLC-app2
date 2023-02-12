<?php

namespace App\View\Components\Homepage;

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
        $userMenuStr = file_get_contents(storage_path() . '/json/configs/view/dashboard/navbarUserMenu.json');
        switch (env('APP_ENV')) {
            case "production":
                $app = 'app2.tlcmodular.com';
                $phpMyAdmin = "192.168.100.100:18102";
                break;
            case "testing":
                $app = 'beta2.tlcmodular.com';
                $phpMyAdmin = "192.168.100.100:28102";
                break;
            case "local":
            default:
                $app = 'localhost:38002';
                $phpMyAdmin = "localhost:38102";
                break;
        }
        $phpMyAdminTable = $phpMyAdmin . "/index.php?route=/sql&pos=0&db=laravel&table=" . $this->table . "&"; //<<Last amp & to compromise the slash /
        $userMenuStr = str_replace("{{app}}", $app, $userMenuStr);
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
        $avatar = $user->avatar ? $user->avatar->url_thumbnail : "";

        return view('components.homepage.menu-profile', [
            'userMenu' => $this->getUserMenu(),
            'user' => $user,
            'avatar' => env("AWS_ENDPOINT") . '/' . env("AWS_BUCKET") . '/' . $avatar,
        ]);
    }
}
