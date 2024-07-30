<?php

namespace App\View\Components\Dashboards;

use App\Http\Controllers\Workflow\LibApps;
use App\Utils\BookmarkTraits\TraitFormatBookmarkEntities;
use Illuminate\View\Component;

class BookmarkGroup extends Component
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
        $allAppsBookmark = array_filter($allApps, fn ($item) => $item['bookmark']);
        return view('components.dashboards.bookmark-group', [
            'allAppsBookmark' => $allAppsBookmark,
        ]);
    }
}
