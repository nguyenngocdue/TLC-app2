<?php

namespace App\View\Components\Renderer;

use App\Http\Controllers\Workflow\LibApps;
use App\Models\Project;
use App\Models\Sub_project;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class DocId extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public function __construct(
        private $dataLine = null,
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return Str::markDocId($this->dataLine);
    }
}
