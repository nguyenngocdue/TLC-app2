<?php

namespace App\View\Components\Print;

use App\Http\Controllers\Workflow\LibApps;
use App\Models\User;
use App\Models\Workplace;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Header5 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $dataSource,
        private $type = '',
        private $page = null,
    ) {
        //
    }

    private function contentHeaderChecklist()
    {
        switch ($this->type) {
            case 'hse_insp_chklst_shts':
                return $this->contentHeaderHseChecklist();
                break;
            case 'qaqc_insp_chklst_shts':
            case 'qaqc_insp_chklst':
                return $this->contentHeaderQaqcChecklist();
                break;

            default:
                # code...
                break;
        }
    }

    private function makeDiv($dataSource)
    {
        $lines = [];
        foreach ($dataSource as $key => $value) {
            $lines[] = "<span class='col-span-2 text-right font-bold p-025vw'>$key </span> <span class='col-span-4 p-025vw'> $value</span>";
        }
        $result = "<div class='text-md-vw border rounded-lg  grid grid-cols-12 w-full border-gray-600'>";
        $result .= join("", $lines);
        $result .= "</div>";
        return $result;
    }
    private function contentHeaderHseChecklist()
    {
        $dataSource = $this->dataSource;
        $location = isset($dataSource->workplace_id) ? Workplace::findFromCache($dataSource->workplace_id)->name : '-';
        $inspector = User::findFromCache($dataSource->owner_id)->name ?? '-';
        $startTime = $dataSource->start_date ?? '-';
        $personIncharge = User::findFromCache($dataSource->assignee_1)->name ?? '-';
        // dump($this->type);
        $data = [
            "Location/Project:" => $location,
            "Start Time:" => $startTime,
            "Inspector:" => $inspector,
            "Person Incharge:" => $personIncharge,
        ];
        return $this->makeDiv($data);
    }
    private function contentHeaderQaqcChecklist()
    {
        $dataSource = $this->dataSource;
        $prodOrder = $dataSource->getProdOrder;
        $prodOrderName = $prodOrder->production_name ?? '';
        $subProject = $prodOrder?->getSubProject;
        $subProjectName = $subProject->name ?? '';
        $project = $subProject?->getProject;
        $projectName = $project->description ?? '';

        // $prodOrderName = $dataSource->getProdOrder->production_name ?? '';
        // $projectName = $dataSource->getProject->description ?? '';
        // $subProjectName = $dataSource->getSubProject->name ?? '';
        $nameCompany = config('company.name') ?? '';
        switch ($this->type) {
            case "qaqc_insp_chklst":
                $prodOrder = $dataSource->getProdOrder;
                break;
            case "qaqc_insp_chklst_shts":
                $prodOrder = $dataSource->getChklst->getProdOrder;
                break;
            default:
                return "Unknown how to render [$this->type]";
        }
        $prodOrderName = $prodOrder->production_name ?? '';
        $subProject = $prodOrder?->getSubProject;
        $subProjectName = $subProject->name ?? '';
        $project = $subProject?->getProject;
        $projectName = $project->description ?? '';
        $data = [
            "Organization:" => $nameCompany,
            "Project:" => $projectName,
            "Sub-Project:" => $subProjectName,
            "Production Name:" => $prodOrderName,
        ];
        return $this->makeDiv($data);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $dataSource = $this->dataSource;
        $contentHeader = $this->contentHeaderChecklist();
        $app = LibApps::getFor($this->type);
        // dump($app);
        return view('components.print.header5', [
            'qrId' => $dataSource->id,
            'type' => $this->type,
            'title' => Str::singular($app['title']),
            'dataSource' => config("company.letter_head"),
            'contentHeader' => $contentHeader,
        ]);
    }
}
