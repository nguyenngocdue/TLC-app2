<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;
use Illuminate\Support\Str;

class LetterHead extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $type,
        private $showId,
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
        $dataSource = [
            'company_logo' => asset('logo/tlc.png'),
            'company_name' => 'TLC MODULAR CONSTRUCTION LIMITED LIABILITY COMPANY',
            'company_address' => '326 Vo Van Kiet Street, Co Giang Ward, District 1, HCMC, Vietnam',
            'company_telephone' => '+84 (0) 28 7306 7779 / +84 (0) 28 7301 8588',
            'company_fax' => '+84 (0) 28 3824 5317',
            'company_email' => 'info@tlcmodular.com',
            'company_website' => 'https://www.tlcmodular.com',
        ];
        return view('components.renderer.letter-head', [
            'dataSource' => $dataSource,
            'id' => $this->showId,
            'type' => Str::plural($this->type),
        ]);
    }
}
