<?php

namespace App\View\Components\Print;

use Illuminate\View\Component;
use Illuminate\Support\Str;

class LetterHead5 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $type,
        private $showId,
        private $dataSource = null,
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
        $nameRenderDocId = Str::markDocId($this->dataSource, $this->type);
        return view('components.print.letter-head5', [
            'dataSource' => config("company.letter_head"),
            'id' => $this->showId,
            'type' => Str::plural($this->type),
            'nameRenderDocId' => $nameRenderDocId,
        ]);
    }
}
