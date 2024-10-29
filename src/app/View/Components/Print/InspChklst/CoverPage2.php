<?php

namespace App\View\Components\Print\InspChklst;

use Illuminate\View\Component;

class CoverPage2 extends Component
{
    function __construct(
        private $avatar = null,
        private $title = "Untitled Page",
        private $dataSource = [],
    ) {}

    function render()
    {
        $params = [
            'avatar' => $this->avatar,
            'title' => $this->title,
            'dataSource' => $this->dataSource,
        ];
        // dump($params);
        return view('components.print.insp-chklst.cover-page2', $params);
    }
}
