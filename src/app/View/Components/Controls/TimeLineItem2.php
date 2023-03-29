<?php

namespace App\View\Components\Controls;

use App\Models\User;
use App\Utils\Constant;
use Carbon\Carbon;
use Illuminate\View\Component;

class TimeLineItem2 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private $dataSource)
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
        $dataSource = $this->dataSource;
        $statusOld = $dataSource->old_value;
        $statusNew = $dataSource->new_value;
        $userId = $dataSource->user_id;
        $user = User::findOrFail($userId);
        $lastName = $user->last_name;
        $positionRendered = $user->position_rendered;
        $nameRender = $lastName . '(' . $positionRendered . ')';
        $time = $dataSource->created_at;
        $timeAgo = Carbon::createFromTimestamp(strtotime($time))->diffForHumans();
        $timeFull = Carbon::createFromFormat(Constant::FORMAT_DATETIME_MYSQL, $time)->format(Constant::FORMAT_DATETIME_ASIAN);
        return view('components.controls.time-line-item2', [
            'statusOld' => $statusOld,
            'statusNew' => $statusNew,
            'timeAgo' => $timeAgo,
            'timeFull' => $timeFull,
            'user' => $user,
            'nameRender' => $nameRender,
            'key' => $dataSource->key
        ]);
    }
}
