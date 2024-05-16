<?php

namespace App\View\Components\Form;

use App\Utils\ClassList;
use Illuminate\View\Component;

class PerPage extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $type = '',
        private $route = '',
        private $perPage = '',
        private $key = '',
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
        $route = route('updateUserSettingsPerPageApi');
        return view('components.form.per-page', [
            'type' => $this->type,
            'route' => $route,
            'perPage' => $this->perPage,
            'classList' => ClassList::DROPDOWN,
            'key' => $this->key,
        ]);
    }
}
