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
    public function __construct(
        private $verticalLayout = false,
        private $flipped = false,
        private $uid = null,
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
        return function (array $data) {
            $slot = json_decode($data['slot']);

            if (is_null($this->uid)) {
                $avatar = null;
                $user = null;
                if (isset($slot->{'id'})) {
                    $user = User::findFromCache($slot->{'id'});
                    $avatar = $user->getAvatarThumbnailUrl();
                }
                if (is_null($user)) return "";

                $title = $slot->{'name'} ?? '';
                $description = $slot->{'position_rendered'} ?? '';
                $gray = $slot->{'resigned'} ?? '';
            } else {
                $user = User::findFromCache($this->uid);
                $title = $user->full_name;
                $description = $user->position_rendered;
                $gray = $user->resigned;
                $avatar = $user->getAvatarThumbnailUrl();
            }

            $href = $slot->{'href'} ?? '';
            $verticalLayout = $this->verticalLayout;
            $tooltip = ($user) ? ($user->resigned ? "This person resigned on " . $user->last_date : "") . " (#$user->id)" : "";
            return "<x-renderer.avatar-item flipped='$this->flipped' tooltip='$tooltip' title='$title' description='$description' href='$href' avatar='$avatar' gray='$gray' verticalLayout='$verticalLayout'></x-renderer.avatar-item>";
        };
    }
}
