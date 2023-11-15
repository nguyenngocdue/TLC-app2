<?php

namespace App\View\Components\Controls\InspChklst;

use App\Models\User;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateTimeConcern;
use Illuminate\View\Component;

class CheckPointSignature extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $table01Name,
        private $rowIndex,
        private $line,
        private $readOnly = false,
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
        $uid = $this->line->inspector_id;
        $timestamp = DateTimeConcern::convertForLoading("picker_datetime", $this->line->created_at);
        $cuid = CurrentUser::id();

        return view('components.controls.insp-chklst.check-point-signature', [
            'table01Name' => $this->table01Name,
            'rowIndex' => $this->rowIndex,
            'line' => $this->line,
            'uid' => $uid,
            'timestamp' => $timestamp,
            'cuid' => $cuid,
            'readOnly' => $this->readOnly,
        ]);
    }
}
