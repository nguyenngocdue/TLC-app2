<?php

namespace App\View\Components\Homepage;

use Illuminate\View\Component;

class SearchModal2 extends Component
{
    public function __construct(
        private $modalId,
    ) {
        //
    }

    public function render()
    {
        return view('components.homepage.search-modal2', [
            'route' => route('updateBookmark'),
            'modalId' => $this->modalId,
        ]);;
    }
}
