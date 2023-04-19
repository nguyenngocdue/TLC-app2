<?php

namespace App\View\Components\Controls\InspChklst;

use App\Models\User;
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
        $signatures = $this->signatures;
        foreach ($signatures as &$signature) {
            $user = User::find($signature['owner_id']);
            $signature['user'] = [
                'id' => $user['id'],
                // 'name' => $user['name'],
                'full_name' => $user['full_name'],
                'position_rendered' => $user['position_rendered'],
                'timestamp' => DateTimeConcern::convertForLoading("picker_datetime", $signature['created_at']),
            ];
        }
        return view('components.controls.insp-chklst.sign-off', [
            'signatures' => $this->signatures
        ]);
    }
}
