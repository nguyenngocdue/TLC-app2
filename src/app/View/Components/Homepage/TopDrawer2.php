<?php

namespace App\View\Components\Homepage;

use App\Http\Controllers\Workflow\LibApps;
use App\Utils\AccessLogger\EntityNameCount;
use App\Utils\Support\CurrentUser;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
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
            // [
            //     'id' => 'reports',
            //     'title' => '<i class="text-green-600 fa-duotone fa-file-chart-column"></i> Reports',
            //     'jsOnMouseOver' => "
            //         $('#topDrawer2Applications').hide(); 
            //         $('#topDrawer2Reports').show(); 
            //         $('#topDrawer2Documents').hide(); 

            //         toggleTabPan('reports');
            //         ",
            // ],
            // [
            //     'id' => 'documents',
            //     'title' => '<i class="text-orange-600 fa-duotone fa-file-lines"></i> Documents',
            //     'jsOnMouseOver' => "
            //         $('#topDrawer2Applications').hide(); 
            //         $('#topDrawer2Reports').hide(); 
            //         $('#topDrawer2Documents').show(); 

            //         toggleTabPan('documents');
            //         ",
            // ],
        ];
        return $tabPans;
    }

    private function getDataSourceApplication()
    {
        $allApps = LibApps::getAll();
        $allApps = array_filter($allApps, function ($app) {
            if (CurrentUser::isAdmin()) {
                $keepForNonAdmin = true;
            } else {
                $keepForNonAdmin = !($app['hidden'] ?? false);
            }

            return $keepForNonAdmin;
        });

        foreach ($allApps as &$app) {
            $app['hidden_for_non_admin'] = !($app['hidden'] ?? false);
            // unset($app['hidden']); // This can do after fully remove old search modal and top drawer
            $app['hidden_navbar'] = !($app['hidden_navbar'] ?? false);
        }

        if (!CurrentUser::isAdmin()) {
            $permissions = CurrentUser::getPermissions();
            $readPermissions = array_filter($permissions, function ($permission) {
                return str_starts_with($permission, 'read-');
            });
            $allApps = array_filter($allApps, function ($app)  use ($readPermissions) {
                return in_array('read-' . Str::plural($app['name']), $readPermissions);
            });
        }

        $bookmarked = CurrentUser::bookmark();
        foreach ($allApps as &$app) $app['bookmarked'] = (in_array($app['name'], $bookmarked));

        $clickCount = (new EntityNameCount)(CurrentUser::id());
        $clickCountArr = [];
        foreach ($clickCount as $line) $clickCountArr[$line->entity_name] = $line->click_count;
        foreach ($allApps as &$app) {
            $app['click_count'] = $clickCountArr[$app['name']] ?? 0;
            if (CurrentUser::isAdmin())
                $app['title'] = $app['title'] . ' <span class="border1 rounded px-1 py-0.5 ml-1 bg-pink-100 text-pink-900">' . $app['click_count'] . '</span>';
        }

        //As app is still a reference to the array, we need to unset it to avoid duplication of the last item
        unset($app);

        uasort($allApps, function ($a, $b) {
            return $b['click_count'] <=> $a['click_count'];
        });

        $appGroups = [];
        foreach ($allApps as $app) {
            if (isset($app['sub_package'])) {
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
        }

        uasort($appGroups, function ($a, $b) {
            return $b['click_count'] <=> $a['click_count'];
        });

        // Log::info($appGroups);

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
