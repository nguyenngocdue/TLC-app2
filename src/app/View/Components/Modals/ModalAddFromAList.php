<?php

namespace App\View\Components\Modals;

use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class ModalAddFromAList extends Component
{
    public function __construct(
        private $modalId = null,
        private $table01Name = null,
        private $xxxForeignKeys = null,
        private $modalBodyName = null,
        private $dataTypeToGetId = null,
    ) {}

    public function render()
    {
        $params = [
            'modalId' => $this->modalId,
            'table01Name' => $this->table01Name,
            'xxxForeignKeys' => $this->xxxForeignKeys,
            'modalBodyName' => $this->modalBodyName,
            'dataTypeToGetId' => $this->dataTypeToGetId,
        ];

        return view('components.modals.modal-add-from-a-list', $params);
    }
}
