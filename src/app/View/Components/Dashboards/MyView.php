<?php

namespace App\View\Components\Dashboards;

use App\Http\Controllers\Workflow\LibApps;
use App\Utils\Support\CurrentUser;
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
    ) {
        //
    }

    private function created_by_me($app, $openingDocs, $uid)
    {
        return $openingDocs->where('owner_id', $uid)->get();
    }
    private function assigned_to_me($app, $openingDocs, $uid, $statuses)
    {
        $docs = $openingDocs->get();
        $result = [];
        foreach ($docs as $doc) {
            if (isset($statuses[$doc->status])) {
                $status = $statuses[$doc->status];
                $assignee_1_to_9 = $status['ball-in-courts']['ball-in-court-assignee'];
                // $monitors = $status['ball-in-courts']['ball-in-court-monitors'];
                $assignee_1_to_9 = $assignee_1_to_9 == 'creator' ? 'owner_id' : $assignee_1_to_9;
                $assignee = $doc->{$assignee_1_to_9};
                // dump($doc->id . ": ($assignee_1_to_9) " . $assignee . " == " . $uid);
                if ($assignee == $uid) $result[] = $doc;
            } else {
                dump("Status " . $doc->status . " of #" . $doc->id . "($app) is not in the available statuses.");
            }
        }
        return $result;
    }
    private function monitored_by_me($app, $openingDocs, $uid, $statuses)
    {
        $docs = $openingDocs->get();
        $result = [];
        // dump("monitored_by_me");
        // dump($docs->count());
        foreach ($docs as $doc) {
            if (isset($statuses[$doc->status])) {
                $status = $statuses[$doc->status];
                // $assignee = $status['ball-in-courts']['ball-in-court-assignee'];
                $monitors_1_to_9 = $status['ball-in-courts']['ball-in-court-monitors'];
                $monitors_1_to_9 = $monitors_1_to_9 ? $monitors_1_to_9 : "getMonitors1()";
                // dump($doc->id, $monitors_1_to_9);
                if (strlen($monitors_1_to_9) > 0) {
                    $fn = substr($monitors_1_to_9, 0, strlen($monitors_1_to_9) - 2);
                    // dump($fn);
                    if (method_exists($doc, $fn)) {
                        // dump("$doc->id $fn");
                        $monitors = $doc->$fn()->pluck('id')->toArray();
                        // dump($monitors);
                        // dump($doc->id . ": ($fn) " . $uid . " in [" . join(", ", $monitors) . "]");
                        if (in_array($uid, $monitors)) $result[] = $doc;
                    } else {
                        dump("$app -> $fn does not exist.");
                        break;
                    }
                }
            } else {
                dump("Status " . $doc->status . " of #" . $doc->id . " ($app) is not in the available statuses.");
            }
        }
        return $result;
    }

    private function makeDataSource($viewType)
    {
        $apps = LibApps::getAll();
        $apps = array_filter($apps, fn ($app) => $app['show_in_my_view'] ?? false);
        $apps = array_keys($apps);
        // dump($apps);
        $uid = CurrentUser::get()->id;
        $result = [];
        foreach ($apps as $app) {
            $sp = SuperProps::getFor($app);
            // dump($app);
            // if (!isset($sp["settings"]['definitions']['closed'])) dump("Definition of closed has not been set for $app");
            $closed = $sp["settings"]['definitions']['closed'] ?? ['closed'];
            $modelPath = Str::modelPathFrom($app);
            $openingDocs = $modelPath::whereNotIn('status', $closed);
            $statuses = $sp['statuses'];
            switch ($viewType) {
                case "assigned_to_me":
                    $result = $this->assigned_to_me($app, $openingDocs, $uid, $statuses);
                    break;
                case "created_by_me":
                    $result =  $this->created_by_me($app, $openingDocs, $uid,);
                    break;
                case "monitored_by_me":
                    $result =  $this->monitored_by_me($app, $openingDocs, $uid, $statuses);
                    break;
                default:
                    dd("Unknown how to render $viewType");
                    break;
            }
            // dump($app, count($openingDocs));
            // dump($app, $closed);
        }
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
                'dataIndex' => 'id',
                'title' => "ID",
            ],
            [
                'dataIndex' => 'doc_type',
            ],
            [
                'dataIndex' => 'name',
                'title' => 'Title',
            ],
            [
                'dataIndex' => 'status',
                'renderer' => 'status',
                'align' => 'center',
            ],
        ];
        // $dataSource = [];
        $dataSource = $this->makeDataSource($this->viewType);
        // if (isset($dataSource[0])) dump($dataSource[0]);
        return view('components.dashboards.my-view', [
            'title' => $this->title,
            'columns' => $columns,
            'dataSource' => $dataSource,
        ]);
    }
}
