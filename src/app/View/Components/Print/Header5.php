<?php

namespace App\View\Components\Print;

use App\Models\User;
use App\Models\Work_area;
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

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $dataSource = $this->dataSource;
        $tableOfContents = $this->tableOfContents;
        $name = $tableOfContents ? $dataSource->name : $dataSource->name;
        $nameRenderOfPageInfo = $dataSource->getQaqcInspTmpl->name ?? '';
        $contentHeader = $this->contentHeaderChecklist();
        $consentNumber = $this->tableOfContents ? '' : (($dataSource->getChklst) ? $dataSource->getChklst->consent_number : "");
        return view('components.print.header5', [
            'tableOfContents' => $tableOfContents,
            'name' => $name,
            'nameRenderOfPageInfo' => $nameRenderOfPageInfo,
            'id' => $dataSource->slug,
            'qrId' => $dataSource->id,
            'consentNumber' => $consentNumber,
            'contentHeader' => $contentHeader,
            'type' => $this->type,
            'dataSource' => config("company.letter_head"),
            'page' => $this->page,
        ]);
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
    private function contentHeaderHseChecklist()
    {
        $dataSource = $this->dataSource;
        $location = isset($dataSource->workplace_id) ? Workplace::findFromCache($dataSource->workplace_id)->name : '-';
        $inspector = User::findFromCache($dataSource->owner_id)->name ?? '-';
        $startTime = $dataSource->start_date ?? '-';
        $personIncharge = User::findFromCache($dataSource->assignee_1)->name ?? '-';
        return "<div class='flex flex-1 justify-center'>
                        <div class='flex flex-col pr-2  font-medium text-base'>
                            <span>Location/Project:</span>
                            <span>Start Time:</span>
                            <span>Inspector:</span>
                            <span>Person Incharge:</span>
                        </div>
                        <div class='flex flex-col font-light text-base'>
                            <span>$location</span>
                            <span>$startTime</span>
                            <span>$inspector</span>
                            <span>$personIncharge</span>
                        </div>
                    </div>
                    <div class='flex flex-1 justify-center'></div>";
        // <div class='flex flex-1 justify-center'>
        //     <div class='flex flex-col pr-2  font-medium text-base'>
        //         <span>Start Time:</span>
        //         <span>Finish Time:</span>
        //     </div>
        //     <div class='flex flex-col font-light text-base'>
        //         <span>$startTime</span>
        //         <span>$finishTime</span>
        //     </div>
        // </div>";
    }
    private function contentHeaderQaqcChecklist()
    {
        $dataSource = $this->dataSource;
        $projectName = $dataSource->getProject->name ?? '';
        $subProjectName = $dataSource->getSubProject->name ?? '';
        $prodOrderName = $dataSource->getProdOrder->name ?? '';
        $nameCompany = config('company.name') ?? '';
        if ($this->tableOfContents) {
            return "<div class='flex flex-1 justify-center'>
                        <div class='flex flex-col pr-2  font-medium text-base'>
                            <span>Organization:</span>
                            <span>Project Name:</span>
                        </div>
                        <div class='flex flex-col font-light text-base'>
                            <span>$nameCompany</span>
                            <span>$projectName</span>
                        </div>
                    </div>
                    <div class='flex flex-1 justify-center'>
                        <div class='flex flex-col pr-2  font-medium text-base'>
                            <span>Sub Project Name:</span>
                            <span>Prod Order Name:</span>
                        </div>
                        <div class='flex flex-col font-light text-base'>
                            <span>$subProjectName</span>
                            <span>$prodOrderName</span>
                        </div>
                    </div>";
        }
        return " <div class='flex'>
                    <div class='flex flex-col pr-2  font-medium text-base'>
                        <span>Organization:</span>
                        <span>Project Name:</span>
                        <span>Sub Project Name:</span>
                        <span>Prod Order Name:</span>
                    </div>
                    <div class='flex flex-col font-light text-base'>
                        <span>$nameCompany</span>
                        <span>$projectName</span>
                        <span>$subProjectName</span>
                        <span>$prodOrderName</span>
                    </div>
                </div>";
    }
}
