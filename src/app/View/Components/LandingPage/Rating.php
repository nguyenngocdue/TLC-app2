<?php

namespace App\View\Components\LandingPage;

use Illuminate\View\Component;

class Rating extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $rating = 5,
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

        $true = $this->rating;
        if($true > 5 || $true < 0) $true = 5;
        $initRating = array_fill(0,$true,true);
        $initRating = array_pad($initRating,5,false);
        return view('components.landing-page.rating',[
            "initRating" => $initRating
        ]);
    }
}
