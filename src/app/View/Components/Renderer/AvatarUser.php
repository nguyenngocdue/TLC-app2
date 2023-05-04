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
            if (isset($slot->{'id'})) {
                $model = ($cuId == $slot->{'id'}) ? CurrentUser::get() : User::find($slot->{'id'});
                $avatar = $model->avatar;
                if ($avatar) $avatar = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/' . $avatar->url_thumbnail;
            }
            $title = $slot->{'name'} ?? '';
            $description = $slot->{'position_rendered'} ?? '';
            $href = $slot->{'href'} ?? '';
            $gray = $slot->{'resigned'} ?? '';
            $verticalLayout = $this->verticalLayout;
            return "<x-renderer.avatar-item title='$title' description='$description' href='$href' avatar='$avatar' gray='$gray' verticalLayout='$verticalLayout'></x-renderer.avatar-item>";
        };
    }
}
