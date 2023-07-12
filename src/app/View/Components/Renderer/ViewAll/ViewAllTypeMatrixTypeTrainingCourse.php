<?php

namespace App\View\Components\Renderer\ViewAll;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllFunctions;
use App\Models\Hr_training_course;
use App\Models\Hr_training_line;
use App\Models\User;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ViewAllTypeMatrixTypeTrainingCourse extends ViewAllTypeMatrixParent
{
    use TraitViewAllFunctions;
    private $workplace_id;
    protected $xAxis = Hr_training_course::class;
    protected $dataIndexX = "training_course_id";
    protected $yAxis = User::class;
    protected $dataIndexY = "user_id";
    protected $rotate45Width = 400;
    protected $groupBy = null;
    protected $allowCreation = false;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        [$this->workplace_id] = $this->getUserSettings();
        $this->workplace_id = $this->workplace_id ? $this->workplace_id : 2;
    }

    private function getUserSettings()
    {
        $type = Str::plural($this->type);
        $settings = CurrentUser::getSettings();
        $workplace_id = $settings[$type][Constant::VIEW_ALL]['matrix']['workplace_id'] ?? null;
        return [$workplace_id];
    }

    protected function getXAxis()
    {
        $result = [];
        $data = Hr_training_course::all();
        // $data = Prod_routing::find($this->prodRouting)->getWirDescriptions();
        foreach ($data as $line) {
            $result[] = [
                'dataIndex' => $line->id,
                'title' => $line->name,
                'align' => 'center',
                'width' => 40,
            ];
        }
        usort($result, fn ($a, $b) => $a['title'] <=> $b['title']);
        return $result;
    }

    protected function getYAxis()
    {
        $timeFrame = Carbon::parse()->subWeek(4)->format(Constant::FORMAT_DATE_MYSQL);
        $data = ($this->yAxis)::query()
            ->whereNot('resigned', true)
            ->where("first_date", '>', $timeFrame)
            ->where('workplace', $this->workplace_id)
            ->orderBy('name')
            ->get();

        return $data;
    }

    protected function getViewportParams()
    {
        return [
            'workplace_id' => $this->workplace_id,
        ];
    }

    protected function getMatrixDataSource($xAxis)
    {
        $lines = Hr_training_line::query()
            ->get();
        // dump($lines);
        return $lines;
    }

    // protected function getCreateNewParams($x, $y)
    // {
    //     // dump($x);
    //     // dump($y);
    //     // dd();
    //     $params = parent::getCreateNewParams($x, $y);
    //     $params['project_id'] =  $this->project;
    //     $params['sub_project_id'] =  $this->subProject;
    //     $params['prod_routing_id'] =  $this->prodRouting;

    //     $params['prod_order_id'] =  $y->id;
    //     // $params['prod_discipline_id'] =  $x['prod_discipline_id'];
    //     // $params['assignee_1'] =  $x['def_assignee'];
    //     return $params;
    // }
}
