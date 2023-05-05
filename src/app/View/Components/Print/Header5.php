<?php

namespace App\View\Components\Print;

use Illuminate\View\Component;

class Header5 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $tableOfContents = false,
        private $dataSource,
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
        $name = $tableOfContents ? $dataSource->name : $dataSource->description;
        $nameRenderOfPageInfo = $dataSource->getQaqcInspTmpl->name ?? '';
        $projectName = $dataSource->getProject->name ?? '';
        $subProjectName = $dataSource->getSubProject->name ?? '';
        $prodOrderName = $dataSource->getProdOrder->name ?? '';
        $consentNumber = $this->tableOfContents ? '' : (($dataSource->getChklst) ? $dataSource->getChklst->consent_number : "");
        return view('components.print.header5', [
            'tableOfContents' => $tableOfContents,
            'name' => $name,
            'nameRenderOfPageInfo' => $nameRenderOfPageInfo,
            'id' => $dataSource->slug,
            'qrId' => $dataSource->id,
            'projectName' => $projectName,
            'subProjectName' => $subProjectName,
            'prodOrderName' => $prodOrderName,
            'consentNumber' => $consentNumber,
            'type' => $this->type,
            'dataSource' => config("company.letter_head"),
            'page' => $this->page,
        ]);
    }
}
