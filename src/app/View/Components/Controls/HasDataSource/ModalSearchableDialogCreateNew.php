<?php

namespace App\View\Components\Controls\HasDataSource;

use App\Utils\ClassList;
use Illuminate\View\Component;

class ModalSearchableDialogCreateNew extends Component
{
    function __construct(
        private $modalId,
        private $multipleStr = '',
        private $tableName = '',
    ) {}

    function render()
    {
        $classList = ClassList::TEXT;
        return view('components.controls.has-data-source.modal-searchable-dialog-create-new', [
            'classList' => $classList,
            'modalId' => $this->modalId,
            'multipleStr' => $this->multipleStr,
            'tableName' => $this->tableName,
        ]);
    }
}
