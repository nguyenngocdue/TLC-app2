<?php

namespace App\View\Components\Homepage;

use App\Http\Controllers\Workflow\LibApps;
use App\Utils\BookmarkTraits\TraitFormatBookmarkEntities;
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
        $allApps = $this->getDataSource(LibApps::getAllShowBookmark());
        $allAppsTopDrawer = $this->getDataSource(LibApps::getAllNavbarBookmark());
        $allAppsTopDrawer = $this->formatDataSource($allAppsTopDrawer);
        return view('components.homepage.top-drawer', [
            'allApps' => array_values($allApps),
            'allAppsTopDrawer' => array_values($allAppsTopDrawer),
            'route' => route('updateBookmark'),
        ]);
    }
}
