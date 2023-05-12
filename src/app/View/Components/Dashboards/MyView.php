<?php

namespace App\View\Components\Dashboards;

use App\Http\Controllers\Workflow\LibApps;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Json\SuperDefinitions;
use App\Utils\Support\Json\SuperProps;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class MyView extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $title = "Untitled",
        private $viewType = null,
        private $projectId = null,
    ) {
        //
    }

    private function getAppsHaveProjectColumn()
    {
        return [
            'pj_shipment',
            'qaqc_mir',
            'qaqc_ncr',
            'qaqc_wir',
        ];
    }

    private function makeUpLinks($app, $doc)
    {
        // $doc->doc_type = $app['title'];
        $doc->doc_type = "<a class='text-blue-700 cursor-pointer' title='" . $app['title'] . "' href='{$app['href']}'>" . Str::upper($app['nickname']) . "</a>";
        $idText = Str::makeId($doc->id);
        $idHref = route(Str::plural($app['name']) . ".edit", $doc->id);
        $doc->id_link = "<a class='text-blue-700' href='$idHref'>$idText</a>";
        return $doc;
    }

    private function created_by_me($appKey, $app, $openingDocs, $uid)
    {
        $result = [];
        $docs = $openingDocs
            ->where('owner_id', $uid)
            ->orderBy('updated_at', 'desc');
        // dump($docs->toSql());
        $docs = $docs->get();

        foreach ($docs as $doc) {
            $this->makeUpLinks($app, $doc);
            $result[] = $doc;
        }
        return $result;
    }

    private function assigned_to_me($appKey, $app, $openingDocs, $uid, $statuses)
    {
        $docs = $openingDocs
            ->where('status', '!=', 'new')
            ->orderBy('updated_at', 'desc')
            ->get();
        $result = [];
        foreach ($docs as $doc) {
            if (isset($statuses[$doc->status])) {
                $assignee = $doc->getCurrentBicId();
                // $status = $statuses[$doc->status];
                // $assignee_1_to_9 = $status['ball-in-courts']['ball-in-court-assignee'];
                // // $monitors = $status['ball-in-courts']['ball-in-court-monitors'];
                // $assignee_1_to_9 = $assignee_1_to_9 == 'creator' ? 'owner_id' : $assignee_1_to_9;
                // $assignee = $doc->{$assignee_1_to_9};
                // // dump($doc->id . ": ($assignee_1_to_9) " . $assignee . " == " . $uid);

                if ($assignee == $uid) {
                    $this->makeUpLinks($app, $doc);
                    $result[] = $doc;
                }
            } else {
                dump("Status " . $doc->status . " of $appKey#" . $doc->id . " is not in the available statuses.");
            }
        }
        return $result;
    }

    // private function monitored_by_me($appKey, $app, $openingDocs, $uid, $statuses)
    // {
    //     $docs = $openingDocs->get();
    //     $result = [];
    //     // dump("monitored_by_me");
    //     // dump($docs->count());
    //     foreach ($docs as $doc) {
    //         if (isset($statuses[$doc->status])) {
    //             $status = $statuses[$doc->status];
    //             // $assignee = $status['ball-in-courts']['ball-in-court-assignee'];
    //             $monitors_1_to_9 = $status['ball-in-courts']['ball-in-court-monitors'];
    //             $monitors_1_to_9 = $monitors_1_to_9 ? $monitors_1_to_9 : "getMonitors1()";
    //             // dump($doc->id, $monitors_1_to_9);
    //             if (strlen($monitors_1_to_9) > 0) {
    //                 $fn = substr($monitors_1_to_9, 0, strlen($monitors_1_to_9) - 2);
    //                 // dump($fn);
    //                 if (method_exists($doc, $fn)) {
    //                     // dump("$doc->id $fn");
    //                     $monitors = $doc->$fn()->pluck('id')->toArray();
    //                     // dump($monitors);
    //                     // dump($doc->id . ": ($fn) " . $uid . " in [" . join(", ", $monitors) . "]");

    //                     if (in_array($uid, $monitors)) $result[] = $doc;
    //                 } else {
    //                     dump("$appKey -> $fn does not exist.");
    //                     break;
    //                 }
    //             }
    //         } else {
    //             dump("Status " . $doc->status . " of $appKey#" . $doc->id . " is not in the available statuses.");
    //         }
    //     }
    //     return $result;
    // }

    private function makeDataSource($viewType)
    {
        $apps = LibApps::getAll();
        $apps = array_filter($apps, fn ($app) => $app['show_in_my_view'] ?? false);
        $uid = CurrentUser::get()->id;
        $result = [];
        foreach ($apps as $appKey => $app) {
            $sp = SuperProps::getFor($appKey);
            // if (!isset($sp["settings"]['definitions']['closed'])) dump("Definition of closed has not been set for $appKey");
            // $closed = $sp["settings"]['definitions']['closed'] ?? ['closed'];
            $closed = SuperDefinitions::getClosedOf($appKey);
            $modelPath = Str::modelPathFrom($appKey);

            $openingDocs = $modelPath::whereNotIn('status', $closed);
            $hasProjectIdColumn = in_array($appKey, $this->getAppsHaveProjectColumn());
            if ($this->projectId) {
                if ($hasProjectIdColumn) {
                    $openingDocs = $openingDocs->where('project_id', $this->projectId);
                } else {
                    continue;
                }
            }
            // dump($appKey . " - " . $openingDocs->count());
            $statuses = $sp['statuses'];
            switch ($viewType) {
                case "assigned_to_me":
                    $items = $this->assigned_to_me($appKey, $app, $openingDocs, $uid, $statuses);
                    break;
                case "created_by_me":
                    $items =  $this->created_by_me($appKey, $app, $openingDocs, $uid,);
                    break;
                    // case "monitored_by_me":
                    //     $items =  $this->monitored_by_me($appKey, $app, $openingDocs, $uid, $statuses);
                    //     break;
                default:
                    dd("Unknown how to render $viewType");
                    break;
            }
            // dump($appKey, count($openingDocs));
            // dump($appKey, $closed);
            $result = [...$result, ...$items];
        }
        // dd($result);
        return $result;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $columns = [
            [
                'dataIndex' => 'id_link',
                'title' => "ID",
                'width' => 100,
            ],
            [
                'dataIndex' => 'doc_type',
                'width' => 300,
            ],
            [
                'dataIndex' => 'name',
                'title' => 'Title',
                'width' => 500,
            ],
            [
                'dataIndex' => 'status',
                'renderer' => 'status',
                'align' => 'center',
                'width' => 100,
            ],
            [
                'dataIndex' => 'due_date',
                'renderer' => 'date-time',
                'align' => 'center',
                'width' => 100,
            ],
        ];
        if (CurrentUser::isAdmin()) {
            $columns[] =
                [
                    'dataIndex' => 'project_id',
                    'title' => 'Project ID<br/><i class="fa-duotone fa-eye"></i>',
                    'align' => 'center',
                    'width' => 100,
                ];
        }
        // $dataSource = [];
        $dataSource = $this->makeDataSource($this->viewType);
        // if (isset($dataSource[0])) dump($dataSource[0]);

        if ($this->viewType === "assigned_to_me") $icon = "fa-duotone fa-inbox-in";
        if ($this->viewType === "created_by_me") $icon = "fa-duotone fa-inbox-out";

        return view('components.dashboards.my-view', [
            'title' => $this->title,
            'columns' => $columns,
            'dataSource' => $dataSource,
            'icon' => $icon,
        ]);
    }
}
