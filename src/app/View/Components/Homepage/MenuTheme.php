<?php

namespace App\View\Components\Homepage;

use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;

class MenuTheme extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $route = route('updateUserSettings');
        $settings = CurrentUser::getSettings();
        $themeBg = $settings['global']['theme-bg'] ?? 'gray-100';

        return view('components.homepage.menu-theme', [
            'route' => $route,
            'themeBg' => $themeBg,
        ]);
    }
}
