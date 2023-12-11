<?php

namespace App\View\Components\Navigation;

use Illuminate\View\Component;

class TableOfContents extends Component
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
            'components.navigation.table-of-contents',
            [
                'dataSource' => $this->dataSource,
                'parentCounter' => $this->parentCounter,
            ]
        );
    }
}
