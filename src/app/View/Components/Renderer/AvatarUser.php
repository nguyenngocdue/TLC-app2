<?php

namespace App\View\Components\Renderer;

use App\Models\User;
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
        private $icon = false,
        private $title = null,
        private $description = null,
        private $showCompany = false,
    ) {
        //
    }

    private function renderOneUserBySlot($slot, $shortForm)
    {
        $avatar = null;
        $user = null;
        $companyName = null;
        if (isset($slot->{'id'})) {
            $user = User::findFromCache($slot->{'id'});
            $avatar = $user->getAvatarThumbnailUrl();
            $companyName = $user->getUserCompany?->name;
        }
        if (is_null($user)) return null;

        $title = $shortForm ? ($slot->{"first_name"} ?? "") : ($slot->{'name0'} ?? '');
        $description = $shortForm ? "" : ($slot->getPosition->name ?? '');
        $gray = $slot->{'resigned'} ?? '';
        $href = $slot->{'href'} ?? '';

        return [$user, $avatar, $title, $description, $href, $companyName, $gray];
    }

    private function renderOneUserByAttribute()
    {
        $user = User::findFromCache($this->uid);
        $avatar = $user->getAvatarThumbnailUrl();
        $title = $user->full_name;
        $description = $user->getPosition->name ?? '';
        $companyName = $user->getUserCompany?->name;
        $gray = $user->resigned;
        $href = "";
        return [$user, $avatar, $title, $description, $href, $companyName, $gray];
    }

    function renderOneUserSlot($slot, $index, $shortForm = false)
    {
        if (is_null($this->uid)) {
            $array = $this->renderOneUserBySlot($slot, $shortForm);
            if (is_null($array)) return;
        } else {
            $array = $this->renderOneUserByAttribute();
        }
        [$user, $avatar, $title, $description, $href, $companyName, $gray]  = $array;

        if (!is_null($this->title)) $title = $this->title;
        if (!is_null($this->description)) $description = $this->description;
        if ($this->showCompany) $description = $description . "<br/><b>" . $companyName . "</b>";

        $verticalLayout = $this->verticalLayout;
        $tooltip = ($user) ? ($user->resigned ? "This person resigned on " . $user->last_date : "") . " (#$user->id)" : "";
        if ($shortForm) $tooltip  = $user->name . "\n" . ($user->getPosition->name ?? "") . "\n" . $tooltip;
        $class = ($shortForm && $index >= 2) ? "-mt-4" : "";
        if ($this->icon) {
            return "<div  style='width:{$this->icon}px; height:{$this->icon}px;' title='$title - $description $tooltip' ><img src='$avatar' class='rounded-full'/></div>";
        } else {
            return "<x-renderer.avatar-item class='$class' flipped='$this->flipped' tooltip='$tooltip' title='$title' description='$description' href='$href' avatar='$avatar' gray='$gray' verticalLayout='$verticalLayout'></x-renderer.avatar-item>";
        }
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
            $result = [];
            // $slotArray = is_array($slot) ? $slot : [$slot];

            if (is_array($slot)) {
                foreach ($slot as $index => $slotScalar) {
                    $result[] = $this->renderOneUserSlot($slotScalar, $index, true);
                }
                $div = "<div class='grid grid-cols-2'>";
                $div .= join("", $result);
                $div .= "</div>";
                return $div;
            } else {
                return $this->renderOneUserSlot($slot, 0);
            }
            // return join("", $result);
        };
    }
}
