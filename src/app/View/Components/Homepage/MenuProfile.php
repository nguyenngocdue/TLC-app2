<?php

namespace App\View\Components\Homepage;

use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;

class MenuProfile extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
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
        // $userMenuStr = str_replace("{{hostname}}", $hostname, $userMenuStr);
        $userMenuStr = str_replace("{{app}}", $app, $userMenuStr);
        $userMenuStr = str_replace("{{phpMyAdmin}}", $phpMyAdmin, $userMenuStr);

        $userMenu = json_decode($userMenuStr, true);
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
