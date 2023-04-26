<?php

namespace App\View\Components;

use App\Utils\Support\CurrentUser;
use App\Utils\System\Timer;
use Illuminate\View\Component;

class Elapse extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $total = false,
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
        if (!env("SHOW_ELAPSE")) return;
        $isAdmin  = CurrentUser::isAdmin();
        if (!$isAdmin) return "";
        $value = "";
        if ($this->total) {
            $value =  "Total: " . Timer::getTimeElapse();
        } else {
            $value = Timer::getTimeElapseFromLastAccess();
        }
        return '<div class="w-full py-1 rounded text-center bg-orange-300"><i class="fa-duotone fa-clock"></i> ' .  $value . "ms</div>";
    }
}
