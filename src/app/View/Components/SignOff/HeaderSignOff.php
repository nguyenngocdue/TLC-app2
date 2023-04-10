<?php

namespace App\View\Components\SignOff;

use Illuminate\View\Component;

class HeaderSignOff extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $dataSource,
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
        $name = $dataSource->description ?? '';
        $projectName = $dataSource->getProject->name ?? '';
        $subProjectName = $dataSource->getSubProject->name ?? '';
        $prodOrderName = $dataSource->getProdOrder->name ?? '';
        $consentNumber = $dataSource->getChklst->consent_number ?? '';
        return view('components.sign-off.header-sign-off', [
            'name' => $name,
            'projectName' => $projectName,
            'subProjectName' => $subProjectName,
            'prodOrderName' => $prodOrderName,
            'consentNumber' => $consentNumber,
        ]);
    }
}
