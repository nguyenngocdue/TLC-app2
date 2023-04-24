<?php

namespace App\View\Components\Controls\InspChklst;

use App\Models\User;
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
        $inspector = User::find($this->line->inspector_id);
        $path = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/';
        $inspector = [
            'id' => $inspector->id,
            'full_name' => $inspector->full_name,
            'position_rendered' => $inspector->position_rendered,
            'timestamp' => DateTimeConcern::convertForLoading("picker_datetime", $this->line->created_at),
            'avatar' => $inspector->avatar ? $path . $inspector->avatar->url_thumbnail : "/images/avatar.jpg",
        ];
        return view('components.controls.insp-chklst.check-point-signature', [
            'table01Name' => $this->table01Name,
            'rowIndex' => $this->rowIndex,
            'line' => $this->line,
            'user' => $inspector,
        ]);
    }
}
