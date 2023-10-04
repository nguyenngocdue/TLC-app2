<?php

namespace App\View\Components\Controls\InspChklst;

use App\Models\User;
use App\Utils\Support\DateTimeConcern;
use Illuminate\View\Component;

class CheckPointSignature extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $table01Name,
        private $rowIndex,
        private $line,
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
        $uid = $this->line->inspector_id;
        $user = null;
        if ($uid) {
            $user = User::findFromCache($uid);
            // $path = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/';
            // dd($user);
            $user = [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'position_rendered' => $user->getPosition->name,
                'timestamp' => DateTimeConcern::convertForLoading("picker_datetime", $this->line->created_at),
                'avatar' => $user->getAvatarThumbnailUrl(),
            ];
        }
        // dump($user);
        return view('components.controls.insp-chklst.check-point-signature', [
            'table01Name' => $this->table01Name,
            'rowIndex' => $this->rowIndex,
            'line' => $this->line,
            'user' => $user,
        ]);
    }
}
