<?php

namespace App\View\Components\LandingPage;

use Illuminate\View\Component;

class Faq extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $dataSource = null,
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.landing-page.faq',
    [
        'dataSource' => $this->dataSource,
    ]);
    }
}
