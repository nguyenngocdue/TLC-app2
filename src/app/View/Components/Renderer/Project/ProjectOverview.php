<?php

namespace App\View\Components\Renderer\Project;

use App\Http\Controllers\Workflow\LibApps;
use App\Utils\Constant;
use App\Utils\Support\Json\SuperDefinitions;
use Carbon\Carbon;
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
        private $table = 'projects',
        private $id = 1,
        private $function = 'getProjectMembers',
    ) {
        //
    }

    private function getColumns()
    {
        $progressTitle = [
            "<div class='p-2 m-1 rounded bg-red-500'></div>Overdue",
            "<div class='p-2 m-1 rounded bg-yellow-500'></div>In 7 days",
            "<div class='p-2 m-1 rounded bg-green-500'></div>More than 7 days",
        ];
        return [
            ['dataIndex' => "doc_type"],
            [
                'dataIndex' => "progress",
                'renderer' => 'progress-bar',
                'title' => "<div class='flex justify-center items-center'>" . join(" ", $progressTitle) . "</div>",
                'width' => '50%',
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
        ];
        foreach ($dataSource as $line) {
            $dueDate = Carbon::createFromFormat(Constant::FORMAT_DATETIME_MYSQL, $line->due_date);
            $diffFromToday = $dueDate->diffInDays(now(), false);
            $dueDateType = ($diffFromToday > 0) ? "overdue" : ($diffFromToday > -7 ? "in_one_week" : "more_than_one_week");
            $result[$dueDateType]['items'][] = $line; //->due_date . " " . $diffFromToday;
        }
        // dump($result);
        return $result;
    }

    function convertToProgressbar($dataSource, $size)
    {
        foreach ($dataSource as $key => &$value) {
            $count = count($value['items']);
            $ids  = join(",", array_map(fn ($i) => $i['id'], $value['items']));
            $value['label'] = $count;
            $value['percent'] = round(100 * $count / $size, 2) . "%";
            switch ($key) {
                case 'in_one_week':
                    $value['color'] = "yellow";
                    $value['href'] = "#$ids";
                    break;
                case 'more_than_one_week':
                    $value['color'] = "green";
                    $value['href'] = "#$ids";
                    break;
                case 'overdue':
                    $value['color'] = "red";
                    $value['href'] = "#$ids";
                    break;
            }
        }
        return $dataSource;
    }

    function makeDataSource($modelPath, $closedArray)
    {
        $items = $modelPath::query();
        $items = $items->where('project_id', $this->id);
        $items = $items->whereNotIn('status', $closedArray);
        $items = $items->get();
        $size = count($items);
        $dataSource = $this->groupByDueDate($items);
        $dataSource = $this->convertToProgressbar($dataSource, $size);
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
            $model = new ($modelPath);
            if (!$model->hasDueDate) continue;
            $closedArray = SuperDefinitions::getClosedOf($appKey);
            [$dataSource, $size] = $this->makeDataSource($modelPath, $closedArray);
            $item =   [
                'doc_type' => "<a class='text-blue-700 cursor-pointer' title='" . $app['title'] . "' href='{$app['href']}'>" . Str::upper($app['nickname']) . "</a>",
                'progress' => $dataSource,
                'total_open' => $size,
            ];
            $result[] = $item;
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
        $project = $modelPath::find($this->id);

        return view('components.renderer.project.project-overview', [
            'project' => $project,
            'columns' => $this->getColumns(),
            'dataSource' => $this->getDataSource(),
        ]);
    }
}
