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
        $allApps = $this->formatDataSource($allApps);
        return view('components.homepage.top-drawer', [
            'allApps' => array_values($allApps),
            'route' => route('updateBookmark'),
        ]);
    }

    private function formatDataSource($allApps)
    {
        $array = [];
        foreach ($allApps as $key => $value) {
            $clickCount = $value['click_count'] ?? 0;
            $packageRender = $value['package_rendered'];
            $subPackageRender = $value['sub_package_rendered'];
            $array[$packageRender]['items'][$subPackageRender]['items'][$key] = $value;
            if (isset($array[$packageRender]['total'])) {
                $array[$packageRender]['total'] += $clickCount;
            } else {
                $array[$packageRender]['total'] = $clickCount;
            }
            if (isset($array[$packageRender]['items'][$subPackageRender]['total'])) {
                $array[$packageRender]['items'][$subPackageRender]['total'] += $clickCount;
            } else {
                $array[$packageRender]['items'][$subPackageRender]['total'] = $clickCount;
            }
        }
        usort($array, function ($a, $b) {
            return $b['total'] - $a['total'];
        });
        $result = [];
        foreach ($array as $key => $value) {
            foreach ($value['items'] as $key => $value2) {
                $result[] = $value2['items'];
            }
        }
        $result = array_reduce($result, function ($carry, $item) {
            return array_merge($carry, $item);
        }, []);
        return $result;
    }
}
