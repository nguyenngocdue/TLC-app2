<?php

namespace App\View\Components\Homepage;

use App\Http\Controllers\Workflow\LibApps;
use App\Utils\BookmarkTraits\TraitFormatBookmarkEntities;
use App\Utils\Support\CurrentUser;
use Illuminate\Support\Arr;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class SearchModal extends Component
{
    use TraitFormatBookmarkEntities;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $modalId,
    ) {
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
        $allAppsTopDrawer = $this->formatDataSource($allApps);
        return view('components.homepage.search-modal', [
            'allApps' => array_values($allApps),
            'allAppsTopDrawer' => array_values($allAppsTopDrawer),
            'currentUserIsAdmin' => CurrentUser::isAdmin(),
            'route' => route('updateBookmark'),
            'modalId' => $this->modalId,
        ]);;
    }
}
