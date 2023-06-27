<?php

namespace App\View\Components\Controls\InspChklst;

use App\Models\Control_type;
use Database\Seeders\FieldSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class CheckPoint extends Component
{
    private static $singletonAttachments = null;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $line,
        private $type,
        private $table01Name,
        private $rowIndex,
        private $debug = false,
        private $attachmentIds,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $controlType = Control_type::getCollection()->pluck('name', 'id',) ?? Control_type::get()->pluck('name', 'id');
        $attachments = $this->line->getMorphManyById($this->attachmentIds, 'insp_photos');
        return view('components.controls.insp-chklst.check-point', [
            'line' => $this->line,
            'controlType' => $controlType,
            'table01Name' => $this->table01Name,
            'rowIndex' => $this->rowIndex,
            'attachments' => $attachments,
            'debug' => $this->debug,
            'type' => $this->type,
        ]);
    }
}
