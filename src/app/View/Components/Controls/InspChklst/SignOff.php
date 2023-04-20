<?php

namespace App\View\Components\Controls\InspChklst;

use App\Models\User;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateTimeConcern;
use Illuminate\View\Component;

class SignOff extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $signatures,
        private $type,
        private $item,
        private $debug = false,
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
        $selectedMonitors = $this->item->getMonitors1();
        $selectedStr = "[" . join(",", $selectedMonitors->pluck('id')->toArray()) . ']';
        $signatures = $this->signatures;
        $path = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/';
        foreach ($signatures as &$signature) {
            $user = User::find($signature['owner_id']);
            $signature['user'] = [
                'id' => $user['id'],
                // 'name' => $user['name'],
                'avatar' => $path . $user->avatar->url_thumbnail,
                'full_name' => $user['full_name'],
                'position_rendered' => $user['position_rendered'],
                'timestamp' => DateTimeConcern::convertForLoading("picker_datetime", $signature['created_at']),
            ];
        }
        $user = CurrentUser::get();
        $currentUser = [
            'id' => $user->id,
            'avatar' => $path . $user->avatar->url_thumbnail,
            'full_name' => $user['full_name'],
            'position_rendered' => $user['position_rendered'],
            'timestamp' => null,
        ];
        // dd($currentUser);
        return view('components.controls.insp-chklst.sign-off', [
            'signatures' => $this->signatures,
            'type' => $this->type,
            'signableId' => $this->item->id,
            'selected' => $selectedStr,
            'currentUser' => $currentUser,
            'input_or_hidden' => $this->debug ? "text" : "hidden",
            'debug' => $this->debug,
        ]);
    }
}
