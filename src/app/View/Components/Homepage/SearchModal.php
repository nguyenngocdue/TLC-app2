<?php

namespace App\View\Components\Homepage;

use App\Http\Controllers\Workflow\LibApps;
use App\Utils\Support\CurrentUser;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class SearchModal extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $allApps = LibApps::getAll();
        return view('components.homepage.search-modal', [
            'allApps' => array_values($allApps),
            'currentUserIsAdmin' => CurrentUser::isAdmin()
        ]);;
    }
}
