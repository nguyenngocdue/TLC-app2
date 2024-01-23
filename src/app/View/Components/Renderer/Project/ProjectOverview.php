<?php

namespace App\View\Components\Renderer\Project;

use App\Http\Controllers\Workflow\LibApps;
use App\Utils\Constant;
use App\Utils\Support\Json\SuperDefinitions;
use App\Utils\Support\JsonControls;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class ProjectOverview extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $table,
        private $title = "Outstanding Tasks",
        private $id = null,
        private $modalId = "modal-over-due-documents",
    ) {
        //
    }

    private function getColumns()
    {
        $progressTitle = [
            "<div class='p-2 m-1 rounded bg-red-300'></div>Overdue",
            "<div class='p-2 m-1 rounded bg-yellow-300'></div>In 7 days",
            "<div class='p-2 m-1 rounded bg-green-300'></div>More than 7 days",
            "<div class='p-2 m-1 rounded bg-orange-300'></div>Unknown Due Date",
        ];
        return [
            ['dataIndex' => "doc_type", 'align' => 'center'],
            [
                'dataIndex' => "progress",
                'renderer' => 'progress-bar',
                'title' => "<div class='flex justify-center items-center'>" . join(" ", $progressTitle) . "</div>",
                'width' => '50%',
                'properties' => ["modalId" => $this->modalId],
            ],
            ['dataIndex' => "total_open", 'align' => 'center'],
        ];
    }

    function groupByDueDate($dataSource)
    {
        $result = [
            "overdue" => ['items' => []],
            "in_one_week" => ['items' => []],
            "more_than_one_week" => ['items' => []],
            "unknown_due_date" => ['items' => []],
        ];
        foreach ($dataSource as $line) {
            if ($line->due_date) {
                $dueDate = Carbon::createFromFormat(Constant::FORMAT_DATETIME_MYSQL, $line->due_date);
                $diffFromToday = $dueDate->diffInDays(now(), false);
                $dueDateType = ($diffFromToday > 0) ? "overdue" : ($diffFromToday > -7 ? "in_one_week" : "more_than_one_week");
                $result[$dueDateType]['items'][] = $line; //->due_date . " " . $diffFromToday;
            } else {
                $result['unknown_due_date']['items'][] = $line;
            }
        }
        // dump($result);
        return $result;
    }

    function convertToProgressbar($dataSource, $appKey, $size)
    {
        if ($size == 0) return [];
        foreach ($dataSource as $key => &$value) {
            $count = count($value['items']);
            $value['label'] = $count;
            $value['id'] = $appKey . "_" . $key;
            $value['classList'] = "cursor-pointer";
            $value['percent'] = round(100 * $count / $size, 2) . "%";
            switch ($key) {
                case 'in_one_week':
                    $value['color'] = "yellow";
                    break;
                case 'more_than_one_week':
                    $value['color'] = "green";
                    break;
                case 'overdue':
                    $value['color'] = "red";
                    break;
                case 'unknown_due_date':
                default:
                    $value['color'] = "orange";
                    break;
            }

            $value['modalKey'] = $appKey . "_" . $key;
        }
        return $dataSource;
    }

    function makeDataSource($appKey, $modelPath, $closedArray)
    {
        $id_column = ($this->table == 'projects') ? "project_id" : "sub_project_id";
        $items = $modelPath::query();
        if (is_null($this->id)) {
            // $items = $items->where($id_column, $this->id);
        } else {
            //<< ECO doesn't have sub_project_id column, only getSubProjectsOfEco()
            if (!in_array($modelPath, ['App\Models\Eco_sheet'])) {
                $items = $items->where($id_column, $this->id);
            }
        }
        $items = $items->whereNotIn('status', $closedArray)->whereNotIn('status', ['not_applicable']);
        // dump($items->toSql());
        $items = $items->get();
        $size = count($items);
        $dataSource = $this->groupByDueDate($items);
        $dataSource = $this->convertToProgressbar($dataSource, $appKey, $size);
        return [$dataSource, $size];
    }

    private function getDataSource()
    {
        $apps = LibApps::getAll();
        $apps = array_filter($apps, fn ($app) => $app['show_in_my_view'] ?? false);

        $result = [];
        foreach ($apps as $appKey => $app) {
            // dump($app);
            $modelPath = Str::modelPathFrom($appKey);
            if (!in_array($appKey, JsonControls::getAppsHaveDueDateColumn())) continue;
            $closedArray = SuperDefinitions::getClosedOf($appKey);
            [$dataSource, $size] = $this->makeDataSource($appKey, $modelPath, $closedArray);
            $item =   [
                'doc_type' => "<a class='text-blue-700 cursor-pointer' title='" . $app['title'] . "' href='{$app['href']}'>" . Str::upper($app['nickname']) . "</a>",
                'progress' => $dataSource,
                'table_name' => Str::plural($appKey),
                'total_open' => $size,
            ];
            $result[$appKey] = $item;
        }

        return $result;
    }

    private function makeModalParams($dataSource)
    {
        // dump($dataSource);
        $result = [];
        foreach ($dataSource as $docType => $docTypeArray) {
            foreach (array_keys($docTypeArray['progress']) as $dueType) {
                $items = $docTypeArray['progress'][$dueType]['items'];
                if (empty($items)) continue;
                $ids = array_map(fn ($i) => $i->id, $items);
                $result[] = [
                    'docType' => $docType,
                    'dueType' => $dueType,
                    'tableName' => $docTypeArray['table_name'],
                    'ids' => $ids,
                    'count' => count($ids),
                ];
            }
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
        $modelPath = Str::modelPathFrom($this->table);
        // dump($modelPath);
        if (is_null($this->id)) {
            $project = $modelPath::all();
        } else {
            $project = $modelPath::find($this->id);
        }
        // dump($project->count());
        $dataSource = $this->getDataSource();
        // dump("Data source count " . count($dataSource));
        // dump($dataSource);
        $modalParams = $this->makeModalParams($dataSource);
        // dump($modalParams);

        return view('components.renderer.project.project-overview', [
            'project' => $project,
            'columns' => $this->getColumns(),
            'dataSource' => $dataSource,
            'modalParams' => $modalParams,
            'title' => $this->title,
            'modalId' => $this->modalId,
        ]);
    }
}
