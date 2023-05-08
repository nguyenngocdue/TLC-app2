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
        private $duration = null,
        private $title = '',
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
        $isAdmin  = CurrentUser::isAdmin();
        if (!$isAdmin) return "";
        $value = "";
        // if (!env("SHOW_ELAPSE")) return;
        if (!env("SHOW_ELAPSE")) {
            // 35: Vo Van Thuc - Software Tester
            // 38: Fortune Truong - Project Manager
            if (!in_array(CurrentUser::id(), [35, 38])) return;
        }

        if ($this->duration) {
            $value = $this->duration;
        } else {
            if ($this->total) {
                $value =  "Total: " . Timer::getTimeElapse();
            } else {
                $value = Timer::getTimeElapseFromLastAccess();
            }
        }
        return '<div class="w-full py-1 m-1 rounded text-center bg-orange-300"><i class="fa-duotone fa-clock"></i> ' . $this->title .  $value . "ms</div>";
    }
}
