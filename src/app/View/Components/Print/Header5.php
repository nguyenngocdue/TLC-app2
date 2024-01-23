<?php

namespace App\View\Components\Print;

use App\Http\Controllers\Workflow\LibApps;
use App\Models\User;
use App\Models\Workplace;
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
        private $tableOfContents = false,
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
        $result = [];
        foreach ($dataSource as $key => $value) {
            $result[] = "<span class='bor1der col-span-3 text-right mr-4 font-medium'>$key</span><span class='col-span-3'>$value</span>";
        }
        return "<div class='border rounded px-10 py-2 grid grid-cols-12 text-base w-full'>" . join("", $result) . "</div>";
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
        $projectName = $dataSource->getProject->name ?? '';
        $subProjectName = $dataSource->getSubProject->name ?? '';
        $prodOrderName = $dataSource->getProdOrder->production_name ?? '';
        $nameCompany = config('company.name') ?? '';
        switch ($this->type) {
            case "qaqc_insp_chklst":
            case "qaqc_insp_chklst_shts":
                $data = [
                    "Organization Name:" => $nameCompany,
                    "Project Name:" => $projectName,
                    "Sub-Project Name:" => $subProjectName,
                    "Production Name:" => $prodOrderName,
                ];
                return $this->makeDiv($data);
            default:
                return "Unknown how to render [$this->type]";
        }
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
            'title' => $app['title'],
            'dataSource' => config("company.letter_head"),
            'contentHeader' => $contentHeader,
        ]);
    }
}
