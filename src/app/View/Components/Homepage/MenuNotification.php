<?php

namespace App\View\Components\Homepage;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class MenuNotification extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $data = json_decode(DB::table('notifications')->select('notifiable_type', 'notifiable_id', 'read_at')->where('read_at', null)->get(), true);

        $modeHasNotification = [];
        foreach ($data as $key => $value) {
            $modeHasNotification[$key] = $value["notifiable_type"];
        }
        $dataCountNotification = array_count_values($modeHasNotification);
        return view('components.homepage.menu-notification')->with(compact('dataCountNotification'));
    }
}
