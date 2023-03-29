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
        private $propsOfMainPage = null,
        private $allProps = null,
    ) {
        //
        // dump($this->props);
        // dump($allProps);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $statusless = !isset($this->allProps['_status']);
        $statusIsVisible = isset($this->propsOfMainPage['_status']);
        if (!$statusIsVisible && !$statusless) {
            $title = "Status field not found";
            $msg = "Please make sure [status] is visible and also hidden, otherwise this document will not be able to change the status.";
            return "<x-feedback.alert message='$msg' type='error' title='$title' />";
        }
    }
}
