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

    private function alreadySigned($signatures, $user)
    {
        $uid = $user->id;
        foreach ($signatures as $signature) {
            // dump($signature->owner_id);
            if ($signature->owner_id == $uid) {
                return true;
            }
        }
        return false;
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
        $currentUser = CurrentUser::get();
        foreach ($signatures as &$signature) {
            $user = User::find($signature['owner_id']);
            $signature['user'] = [
                'id' => $user['id'],
                // 'name' => $user['name'],
                'avatar' => $user->avatar ? $path . $user->avatar->url_thumbnail : "/images/avatar.jpg",
                'full_name' => $user['full_name'],
                'position_rendered' => $user['position_rendered'],
                'timestamp' => DateTimeConcern::convertForLoading("picker_datetime", $signature['created_at']),
            ];
            $signature['updatable'] = $user['id'] == $currentUser->id;
        }
        $currentUserObject = [
            'id' => $currentUser->id,
            'avatar' =>  $currentUser->avatar ? $path . $currentUser->avatar->url_thumbnail : "/images/avatar.jpg",
            'full_name' => $currentUser['full_name'],
            'position_rendered' => $currentUser['position_rendered'],
            'timestamp' => null,
        ];
        // dd($currentUser);

        $alreadySigned = $this->alreadySigned($signatures, $currentUser);
        return view('components.controls.insp-chklst.sign-off', [
            'signatures' => $this->signatures,
            'type' => $this->type,
            'signableId' => $this->item->id,
            'selected' => $selectedStr,
            'currentUser' => $currentUserObject,
            'input_or_hidden' => $this->debug ? "text" : "hidden",
            'debug' => $this->debug,
            'alreadySigned' => $alreadySigned,
        ]);
    }
}
