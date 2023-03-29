<?php

namespace App\View\Components\Controls;

use Illuminate\View\Component;

class StatusVisibilityChecker extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $props = null
    ) {
        //
        // dump($this->props);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if (!isset($this->props['_status'])) {
            $title = "Status field not found";
            $msg = "Please make sure [status] is visible and also hidden, otherwise this document will not be able to change the status.";
            return "<x-feedback.alert message='$msg' type='error' title='$title' />";
        }
    }
}
