<?php

namespace App\View\Components\Calendar;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllFunctions;
use App\Models\User;
use App\Utils\Support\CurrentUser;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;

class SidebarOwnerUser extends Component
{
    use TraitViewAllFunctions;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $type,
        private $timesheetableType = null,
        private $timesheetableId = null,)
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
        $ownerId = ($this->timesheetableType)::findFromCache($this->timesheetableId)->owner_id ?? CurrentUser::id();
        $user = json_encode(User::findFromCache($ownerId));
        $htmlUserRender =  Blade::render("<x-renderer.avatar-user>$user</x-renderer.avatar-user>") ?? '';
        return "<x-renderer.card title='TImeSheet Owner User'>$htmlUserRender</x-renderer.card>";
    }
}
