<?php

namespace App\View\Components\Homepage;

use App\Utils\BookmarkTraits\TraitFormatBookmarkEntities;
use Illuminate\View\Component;

class TopDrawer2 extends Component
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
        [$allAppsRecent, $allApps, $allAppsTopDrawer] = $this->getAllAppsOfSearchModalAndTopDrawer();
        return view('components.homepage.top-drawer2', [
            'buttonTabs' => $this->buttonTabs($allAppsTopDrawer),
            'allAppsRecent' => $allAppsRecent,
            'allApps' => $allApps,
            'allAppsTopDrawer' => $allAppsTopDrawer,
            'route' => route('updateBookmark'),
        ]);
    }
    private function buttonTabs($allAppsTopDrawer)
    {
        $buttonTabs = array_unique(array_column($allAppsTopDrawer, 'package_tab'));
        array_push($buttonTabs, 'recent_document');
        return array_values($buttonTabs);
    }
}
