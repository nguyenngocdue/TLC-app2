<?php

namespace App\View\Components\Homepage;

use App\Http\Controllers\Workflow\LibApps;
use App\Utils\AccessLogger\EntityNameCount;
use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;

class TopDrawer2 extends Component
{
    public function __construct()
    {
        //
    }

    private function getTabPan()
    {
        $tabPans = [
            [
                'id' => 'applications',
                'title' => '<i class="text-blue-600 fa-duotone fa-cabinet-filing"></i> Applications',
                'jsOnMouseOver' => "
                    $('#topDrawer2Applications').show(); 
                    $('#topDrawer2Reports').hide(); 
                    $('#topDrawer2Documents').hide(); 

                    toggleTabPan('applications');
                    ",

                'active' => 1,
            ],
            [
                'id' => 'reports',
                'title' => '<i class="text-green-600 fa-duotone fa-file-chart-column"></i> Reports',
                'jsOnMouseOver' => "
                    $('#topDrawer2Applications').hide(); 
                    $('#topDrawer2Reports').show(); 
                    $('#topDrawer2Documents').hide(); 

                    toggleTabPan('reports');
                    ",
            ],
            [
                'id' => 'documents',
                'title' => '<i class="text-orange-600 fa-duotone fa-file-lines"></i> Documents',
                'jsOnMouseOver' => "
                    $('#topDrawer2Applications').hide(); 
                    $('#topDrawer2Reports').hide(); 
                    $('#topDrawer2Documents').show(); 

                    toggleTabPan('documents');
                    ",
            ],
        ];
        return $tabPans;
    }

    private function getDataSourceApplication()
    {
        $allApps0 = LibApps::getAll();
        // dd($allApps0);
        $allApps = array_filter($allApps0, function ($app) {
            $hiddenNavbar = !($app['hidden_navbar'] ?? false);
            if (CurrentUser::isAdmin()) {
                $hiddenNonAdmin = true;
            } else {
                $hiddenNonAdmin = !($app['hidden'] ?? false);
            }

            return $hiddenNavbar && $hiddenNonAdmin;
        });


        $bookmarked = CurrentUser::bookmark();
        foreach ($allApps as &$app) $app['bookmarked'] = (in_array($app['name'], $bookmarked));

        $clickCount = (new EntityNameCount)(CurrentUser::id());
        $clickCountArr = [];
        foreach ($clickCount as $line) $clickCountArr[$line->entity_name] = $line->click_count;
        foreach ($allApps as &$app) $app['click_count'] = $clickCountArr[$app['name']] ?? 0;
        // dump(array_pop($allApps));
        uasort($allApps, function ($a, $b) {
            return $b['click_count'] <=> $a['click_count'];
        });

        $appGroups = [];
        foreach ($allApps as $app) {
            if (!isset($appGroups[$app['sub_package']])) {
                $appGroups[$app['sub_package']] = [
                    'id' => $app['sub_package'],
                    'title' => $app['sub_package_rendered'],
                    'click_count' => 0,
                    'items' => [],
                ];
            }
            $appGroups[$app['sub_package']]['click_count'] += $app['click_count'];
            $appGroups[$app['sub_package']]['items'][] = $app;
        }
        // dd($appGroups);

        uasort($appGroups, function ($a, $b) {
            return $b['click_count'] <=> $a['click_count'];
        });

        return $appGroups;
    }

    public function render()
    {
        $dataSourceApplication =  $this->getDataSourceApplication();

        return view('components.homepage.top-drawer2', [
            'tabPans' => $this->getTabPan(),
            'dataSourceApplications' => $dataSourceApplication,
            'route' => route('updateBookmark'),
        ]);
    }
}
