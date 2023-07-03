<?php

namespace App\View\Components\Calendar;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllFunctions;
use App\Models\User;
use App\Utils\Support\CurrentUser;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;

class SidebarSelectedUser extends Component
{
    use TraitViewAllFunctions;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private $type)
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
        [, $filterViewAllCalendar] = $this->getUserSettingsViewAllCalendar();
        $ownerId = isset($filterViewAllCalendar['owner_id']) ? $filterViewAllCalendar['owner_id'][0] : CurrentUser::id();
        $user = json_encode(User::findFromCache($ownerId));
        $htmlUserRender =  Blade::render("<x-renderer.avatar-user>$user</x-renderer.avatar-user>") ?? '';
        return "<x-renderer.card title='Selected User'>$htmlUserRender</x-renderer.card>";
    }
}
