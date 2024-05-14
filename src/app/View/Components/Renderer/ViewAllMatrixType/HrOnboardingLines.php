<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllFunctions;
use App\Models\Hr_onboarding_course;
use App\Models\Hr_onboarding_line;
use App\Models\User;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateTimeConcern;
use App\View\Components\Renderer\ViewAll\ViewAllTypeMatrixParent;
use Carbon\Carbon;
use Illuminate\Support\Str;

class HrOnboardingLines extends ViewAllTypeMatrixParent
{
    use TraitViewAllFunctions;
    private $workplace_id;
    protected $xAxis = Hr_onboarding_course::class;
    protected $dataIndexX = "onboarding_course_id";
    protected $yAxis = User::class;
    protected $dataIndexY = "user_id";
    // protected $rotate45Width = 400;
    protected $groupBy = null;
    protected $allowCreation = false;
    protected $tableTopCenterControl = "To create data, please use HR OnBoarding screen.";

    private $timeFrameInDays = 30;
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
        $data = Hr_onboarding_course::all();
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
        // dump($this->workplace_id);
        $workplace_ids = is_array($this->workplace_id) ? $this->workplace_id : [$this->workplace_id];
        $timeFrame = Carbon::parse()->subDays($this->timeFrameInDays)->format(Constant::FORMAT_DATE_MYSQL);
        $data = ($this->yAxis)::query()
            ->whereNot('resigned', true)
            ->where("first_date", '>', $timeFrame)
            ->whereIn('workplace', $workplace_ids)
            ->orderBy('first_date')
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
        $lines = Hr_onboarding_line::query()
            ->get();
        // dump($lines);
        return $lines;
    }

    protected function getMetaColumns()
    {
        return [
            ['dataIndex' => 'employee_id', 'align' => 'center', 'width' => 100],
            ['dataIndex' => 'employee_cat', 'align' => 'center', 'width' => 100],
            ['dataIndex' => 'employee_position', 'align' => 'center', 'width' => 100],
            ['dataIndex' => 'report_to', 'align' => 'center', 'width' => 200],
            ['dataIndex' => 'department_of_trainee', 'align' => 'center', 'width' => 100,],
            ['dataIndex' => 'first_date', 'align' => 'center', 'width' => 100],
            ['dataIndex' => 'due_date', 'align' => 'center', 'width' => 100],
        ];
    }

    function getMetaObjects($y, $dataSource, $xAxis, $forExcel, $matrixKey)
    {
        $dueDate = Carbon::parse($y->first_date)->addDays($this->timeFrameInDays)->format(Constant::FORMAT_DATE_MYSQL);
        return [
            'employee_id' => $y->employeeid,
            'employee_cat' => $y->getUserCat->name,
            'employee_position' => $y->getPosition->name,
            'report_to' => $y->getUserDiscipline->getDefAssignee->name,
            'department_of_trainee' => $y->getUserDepartment->name,
            'first_date' => DateTimeConcern::formatForLoading($y->first_date, Constant::FORMAT_DATE_MYSQL, Constant::FORMAT_DATE_ASIAN),
            'due_date' => DateTimeConcern::formatForLoading($dueDate, Constant::FORMAT_DATE_MYSQL, Constant::FORMAT_DATE_ASIAN),
        ];
    }
}
