<?php

namespace App\View\Components\Renderer;

use App\Models\User;
use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;

class AvatarUser extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private $verticalLayout = false)
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
        return function (array $data) {
            $slot =  json_decode($data['slot']);
            $avatar = null;
            $cuId = CurrentUser::id();
            $user = null;
            if (isset($slot->{'id'})) {
                $user = ($cuId == $slot->{'id'}) ? CurrentUser::get() : User::findFromCache($slot->{'id'});
                $avatar = $user->getAvatarThumbnailUrl();
            }
            $title = $slot->{'name'} ?? '';
            $description = $slot->{'position_rendered'} ?? '';
            $href = $slot->{'href'} ?? '';
            $gray = $slot->{'resigned'} ?? '';
            $verticalLayout = $this->verticalLayout;
            $tooltip = ($user) ? ($user->resigned ? "This person resigned on " . $user->last_date : "") : "";
            return "<x-renderer.avatar-item tooltip='$tooltip' title='$title' description='$description' href='$href' avatar='$avatar' gray='$gray' verticalLayout='$verticalLayout'></x-renderer.avatar-item>";
        };
    }
}
