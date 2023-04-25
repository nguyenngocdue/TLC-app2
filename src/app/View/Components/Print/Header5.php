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
        $consentNumber = $this->tableOfContents ? '' : $dataSource->getChklst->consent_number;
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
            'dataSource' => $this->dataInfoCompany(),
            'page' => $this->page,
        ]);
    }
    private function dataInfoCompany()
    {
        return [
            'company_logo' => asset('logo/tlc.png'),
            'company_name' => 'TLC MODULAR CONSTRUCTION LIMITED LIABILITY COMPANY',
            'company_address' => '326 Vo Van Kiet Street, Co Giang Ward, District 1, HCMC, Vietnam',
            'company_telephone' => '+84 (0) 28 7306 7779 / +84 (0) 28 7301 8588',
            'company_fax' => '+84 (0) 28 3824 5317',
            'company_email' => 'info@tlcmodular.com',
            'company_website' => 'https://www.tlcmodular.com',
        ];
    }
}
