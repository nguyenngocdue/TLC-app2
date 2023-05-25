<?php

namespace App\View\Components\Homepage;

use App\Http\Controllers\Workflow\LibApps;
use App\Utils\AccessLogger\EntityIdClickCount;
use App\Utils\AccessLogger\EntityNameClickCount;
use App\Utils\BookmarkTraits\TraitFormatBookmarkEntities;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class TopDrawer extends Component
{
    use TraitFormatBookmarkEntities;
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
        $allApps = $this->getDataSource(LibApps::getAllNavbarBookmark());
        return view('components.homepage.top-drawer', [
            'allApps' => array_values($allApps),
            'route' => route('updateBookmark'),
        ]);
    }
}
