<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllFunctions;
use App\Learn\Bridge\Bridge;
use App\Learn\Builder\BurgerBuilder;
use App\Learn\Composite\Composite;
use App\Learn\Facade\Facade;
use App\Learn\FactoryMethod\DeveloperManager;
use App\Learn\FactoryMethod\MarketingManager;
use App\Learn\Flyweight\Flyweight;
use App\Learn\Proxy\Proxy;
use App\Learn\SimpleFactory\DoorFactory;
use App\Models\Comment;
use App\Models\Hr_timesheet_officer;
use App\Models\Hr_timesheet_worker;
use App\Models\User;
use App\Models\Zunit_test_03;
use App\Utils\AccessLogger\LoggerAccessRecent;
use App\Utils\ConvertDataUserPosition;
use App\Utils\Storage\Thumbnail;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateTimeConcern;
use App\Utils\Support\Tree\BuildTree;
use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class WelcomeCanhController extends Controller
{
    use TraitViewAllFunctions;
    public function getType()
    {
        return "dashboard";
    }
    public function index(Request $request)
    {
        ConvertDataUserPosition::handle();
        // dd((new LoggerAccessRecent)(CurrentUser::id()));
        // dd(Thumbnail::createThumbnailByOptions('input','output'));
        // $devManager = (new DeveloperManager())->takeInterView();
        // $marketingManager = (new MarketingManager())->takeInterView();
        // $query = QueryBuilder::select();
        // $burger = (new BurgerBuilder(14))
        //                         ->addCheese()
        //                         ->addLettuce()
        //                         ->addPepperoni()
        //                         ->addTomato()
        //                         ->build();
        // Bridge::using();
        // Facade::using();
        // Flyweight::using();
        // Proxy::using();
        // Composite::using();
        // dd();
        // $tree = BuildTree::getTree();
        // $results = [];
        // $showOptions = $this->getUserSettingsViewOrgChart();
        // $this->x($tree,$results,$this->getOptionsRenderByUserSetting($showOptions));
        // usort($results,function($a,$b){
        //     return strcmp($a['name'],$b['name']);
        // });
        return view(
            'welcome-canh',
            ['treeData' => $this->getTreeData(),]
        );
    }
    private function getTreeData()
    {
        return [
            [
                "title" => 'parent 1',
                "key" => '0-0',
                "children" => [
                    [
                        "title" => 'parent 1-0',
                        "key" => '0-0-0',
                        "children" => [
                            [
                                "title" => 'leaf',
                                "key" => '0-0-0-0',
                            ],
                            [
                                "key" => '0-0-0-1',
                            ],
                            [
                                "title" => 'leaf',
                                "key" => '0-0-0-2',
                            ]
                        ],
                    ],
                    [
                        "title" => 'parent 1-1',
                        "key" => '0-0-1',
                        "children" => [
                            [
                                "title" => 'leaf',
                                "key" => '0-0-1-0',
                            ]
                        ],
                    ],
                    [
                        "title" => 'parent 1-2',
                        "key" => '0-0-2',
                        "children" => [
                            [
                                "title" => 'leaf 1',
                                "key" => '0-0-2-0',
                            ],
                            [
                                "title" => 'leaf 2',
                                "key" => '0-0-2-1',
                            ]
                        ],
                    ],
                ]
            ],
            [
                "title" => 'parent 2',
                "key" => '0-1',
                "children" => [
                    [
                        "title" => 'parent 2-0',
                        "key" => '0-1-0',
                        "children" => [
                            [
                                "title" => 'leaf',
                                "key" => '0-1-0-0',
                            ],
                            [
                                "title" => 'leaf',
                                "key" => '0-1-0-1',
                            ]
                        ],
                    ],
                ],
            ]
        ];
    }
    private function x($tree, &$results, $options)
    {
        foreach ($tree as $value) {
            if (isset($value->children)) {
                $this->x($value->children, $results, $options);
            }
            if (App::isProduction()) {
                if ($value->show_on_beta == 0) {
                    $a = $this->convertDataSource($value, $options);
                    if ($a) $results[] = $a;
                }
            } else {
                $a = $this->convertDataSource($value, $options);
                if ($a) $results[] = $a;
            }
        }
    }
    private function convertDataSource($value, $options)
    {
        if (
            in_array($value->resigned, $options['resigned'])
            && in_array($value->time_keeping_type, $options['time_keeping_type'])
        ) {
            $user = User::findFromCache($value->id);
            $positionRendered = $user->getPosition->name;
            $avatar = $user->getAvatarThumbnailUrl() ?? '';
            return [
                'key' => $value->id,
                'name' => $value->name,
                'parent' => $value->parent_id,
                'avatar' => $avatar,
                'fill' => $this->getFillColor($value),
                'title' => $positionRendered,
            ];
        }
    }
    private function getFillColor($item)
    {
        return $item->resigned == 1 ? '#d1d5db' : ($item->time_keeping_type == 3 ? "#fed7aa" : "#ffffff");
    }
    private function getOptionsRenderByUserSetting($showOptions)
    {
        $results = [
            'resigned' => [0],
            'time_keeping_type' => [2, 3],
        ];
        foreach ($showOptions as $key => $value) {
            switch ($key) {
                case 'resigned':
                    if ($value == 'true') $results['resigned'] = [0, 1];
                    break;
                case 'time_keeping_type':
                    if ($value == 'true') $results['time_keeping_type'] = [1, 2, 3];
                    break;
                default:
                    break;
            }
        }
        return $results;
    }
    public function indexAll()
    {
        return view(
            'welcome-canh-all',
        );
    }
}
