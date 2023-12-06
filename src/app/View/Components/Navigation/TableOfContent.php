<?php

namespace App\View\Components\Navigation;

use Illuminate\View\Component;

class TableOfContent extends Component
{
    function __construct(
        private $dataSource = [],
        private $parentCounter = '',
    ) {
    }

    public function render()
    {
        // $this->renderNode($this->dataSource);
        return view(
            'components.navigation.table-of-content',
            [
                'dataSource' => $this->dataSource,
                'parentCounter' => $this->parentCounter,
            ]
        );
    }
}
