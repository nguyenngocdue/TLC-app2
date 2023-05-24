<?php

namespace App\View\Components\Homepage;

use App\Http\Controllers\Workflow\LibApps;
use App\Utils\AccessLogger\EntityIdClickCount;
use App\Utils\AccessLogger\EntityNameClickCount;
use App\Utils\ENV;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class TopDrawer extends Component
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
        $allApps = LibApps::getAllNavbarBookmark();
        $entitiesCurrentUserUsuallyUse = (new EntityNameClickCount)(Auth::id());
        $entitiesName = collect($entitiesCurrentUserUsuallyUse)->pluck('entity_name')->toArray();
        $entities = $this->convertDataEntitiesCurrentUserUsuallyUse($entitiesCurrentUserUsuallyUse);
        if (!empty($entitiesCurrentUserUsuallyUse)) {
            array_walk($allApps, function (&$value, $key) use ($entities) {
                if (($entities[$key] ?? null) && ENV::present()) {
                    $value['click_count'] = $entities[$key]['click_count'];
                }
            });
            $allApps = array_filter(array_replace(array_flip($entitiesName), $allApps), function ($item) {
                return is_array($item);
            });
        }
        return view('components.homepage.top-drawer', [
            'allApps' => array_values($allApps),
            'route' => route('updateBookmark'),
        ]);
    }
    private function convertDataEntitiesCurrentUserUsuallyUse($entitiesCurrentUserUsuallyUse)
    {
        $result = [];
        array_map(function ($item) use (&$result) {
            $result[$item->entity_name] = ['entity_name' => $item->entity_name, 'click_count' => $item->click_count];
        }, $entitiesCurrentUserUsuallyUse);
        return $result;
    }
}
