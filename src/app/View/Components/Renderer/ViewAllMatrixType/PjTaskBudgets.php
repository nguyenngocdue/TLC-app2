<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\Models\Pj_task_budget;
use App\Models\Project;
use App\Models\User_discipline;
use App\View\Components\Renderer\ViewAll\ViewAllTypeMatrixParent;
use Illuminate\Support\Facades\Log;

class PjTaskBudgets extends ViewAllTypeMatrixParent
{
    // use TraitYAxisDiscipline;

    private $project, $subProject, $prodRouting, $prodRoutingLink, $prodDiscipline;
    // protected $viewportMode = null;

    // protected $xAxis = Prod_routing_link::class;
    protected $dataIndexX = "discipline_id";
    protected $yAxis = Project::class;
    protected $dataIndexY = "project_id";
    protected $rotate45Width = 300;
    protected $rotate45Height = 250;
    protected $tableTrueWidth = true;
    protected $headerTop = 20;
    protected $groupBy = null;
    // protected $mode = 'status';

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        // [$this->project, $this->subProject, $this->prodRouting, $this->prodRoutingLink, $this->prodDiscipline] = $this->getUserSettings();
        // $this->project = $this->project ? $this->project : 5;
        // $this->subProject = $this->subProject ? $this->subProject : null;
        // $this->prodRouting = $this->prodRouting ? $this->prodRouting : null;
        // $this->prodRoutingLink = $this->prodRoutingLink ? $this->prodRoutingLink : [];
    }

    // private function getUserSettings()
    // {
    //     $type = Str::plural($this->type);
    //     $settings = CurrentUser::getSettings();
    //     $project = $settings[$type][Constant::VIEW_ALL]['matrix']['project_id'] ?? null;
    //     $subProject = $settings[$type][Constant::VIEW_ALL]['matrix']['sub_project_id'] ?? null;
    //     $prodRouting = $settings[$type][Constant::VIEW_ALL]['matrix']['prod_routing_id'] ?? null;
    //     $prodRoutingLink = $settings[$type][Constant::VIEW_ALL]['matrix']['prod_routing_link_id'] ?? null;
    //     $prodDiscipline = $settings[$type][Constant::VIEW_ALL]['matrix']['prod_discipline_id'] ?? null;
    //     return [$project, $subProject, $prodRouting, $prodRoutingLink, $prodDiscipline];
    // }

    public function getYAxis()
    {
        $yAxis = $this->yAxis::query()
            // ->whereNotIn('status', ['concept', 'closed', 'cancelled'])
            ->where('show_in_task_budget', true)
            ->orderBy('name')
            ->get();
        return $yAxis;
    }

    protected function getXAxisPrimaryColumns()
    {
        $data = User_discipline::query()
            // ->whereHas("getUsers", $getUserFn)
            ->where('show_in_task_budget', true)
            ->with(["getUsers" => function ($query) {
                $query
                    ->with("getAvatar")
                    ->where('resigned', false);
            }])
            ->orderBy('name')
            ->get();
        // dump(sizeof($data));
        return $data;
    }

    protected function getXAxis()
    {
        $result = [];
        $data = $this->getXAxisPrimaryColumns();
        // dump($data);
        foreach ($data as $line) {
            $avatars = $line->getUsers->map(function ($user) {
                if (!$user->getAvatar) $src = "/image/avatar.jpg";
                else $src = app()->pathMinio() . $user->getAvatar->url_thumbnail;
                return "<img src='$src' class='w-8 h-8 rounded-full'>";
            })->join("");
            $item = [
                'dataIndex' => $line->id,
                'columnIndex' => "status",
                'title' => $line->name . "<br/><div class='flex'>" . $avatars . "</div>",
                'width' => 65,
            ];
            $result[] = $item;
        }
        return $result;
        // usort($result, fn ($a, $b) => $a['title'] <=> $b['title']);
        // return $result;
    }

    public function getMatrixDataSource($xAxis)
    {
        $lines = Pj_task_budget::query()->get();
        // ->where('sub_project_id', $this->subProject)
        // ->where('prod_routing_id', $this->prodRouting);
        // if ($this->prodDiscipline) $lines = $lines->where('prod_discipline_id', $this->prodDiscipline);
        // if ($this->prodRoutingLink) $lines = $lines->whereIn('prod_routing_link_id', $this->prodRoutingLink);
        // dump($lines[0]);
        return $lines;
    }

    protected function getViewportParams()
    {
        return [
            'project_id' => $this->project,
            'sub_project_id' => $this->subProject,
            'prod_routing_id' => $this->prodRouting,
            'prod_routing_link_id' => $this->prodRoutingLink,
            'prod_discipline_id' => $this->prodDiscipline,
        ];
    }

    protected function getCreateNewParams($x, $y)
    {
        $params = parent::getCreateNewParams($x, $y);
        $params['status'] =  'new';
        // $params['project_id'] =  $y->id;
        // $params['discipline_id'] =  $x['id'];
        return $params;
    }
}
