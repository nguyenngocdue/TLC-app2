<?php

namespace App\View\Components\Dashboards;

use App\Http\Controllers\Workflow\LibApps;
use Illuminate\View\Component;

class BookmarkGroup extends Component
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
        $isShowMore = false;
        $allApps = LibApps::getAllShowBookmark();
        $allAppsBookmark = array_filter($allApps, fn ($item) => $item['bookmark']);
        if (sizeof($allAppsBookmark) > 10) {
            $isShowMore = true;
        }
        $arrayBookmarkFirst = array_slice($allAppsBookmark, 0, 10, true);
        $array = array_slice($allAppsBookmark, 10);
        $arrayBookmarkSecond = array_merge($array);
        return view('components.dashboards.bookmark-group', [
            'isShowMore' => $isShowMore,
            'arrayBookmarkFirst' => $arrayBookmarkFirst,
            'arrayBookmarkSecond' => $arrayBookmarkSecond
        ]);
    }
}
