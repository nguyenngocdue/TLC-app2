<?php

namespace App\View\Components\Modals;

use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class ModalAddFromAList extends Component
{
    public function __construct(
        private $modalId = null,
        private $table01Name = null,

        // private $eloquentFunctionName = null,
        // private $groupDataSourceName = null,
        // private $itemDataSourceName = null,
        private $xxxForeignKey = null,
        private $modalBodyName = null,
    ) {}

    public function render()
    {
        $params = [
            'modalId' => $this->modalId,
            'table01Name' => $this->table01Name,

            // 'eloquentFunctionName' => $this->eloquentFunctionName,
            // 'groupDataSourceName' => $this->groupDataSourceName,
            // 'itemDataSourceName' => $this->itemDataSourceName,
            'xxxForeignKey' => $this->xxxForeignKey,
            'modalBodyName' => $this->modalBodyName,
        ];

        return view('components.modals.modal-add-from-a-list', $params);
    }
}
