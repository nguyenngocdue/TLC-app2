<?php

namespace App\View\Components\Homepage;

use Illuminate\View\Component;

class TopDrawer2Applications extends Component
{
    public function __construct(
        private $dataSource,
    ) {
        //
    }

    public function render()
    {
        return view('components.homepage.top-drawer2-applications', [
            'route' => route('updateBookmark'),
            'dataSource' => $this->dataSource,
        ]);
    }
}
