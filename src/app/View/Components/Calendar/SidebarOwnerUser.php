<?php

namespace App\View\Components\Calendar;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllFunctions;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewEditFunctions;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;

class SidebarOwnerUser extends Component
{
    use TraitViewAllFunctions;
    use TraitViewEditFunctions;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $type,
        private $timesheetableType = null,
        private $timesheetableId = null,
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
        // $ownerId = ($this->timesheetableType)::findFromCache($this->timesheetableId)->owner_id ?? CurrentUser::id();
        $owner = $this->getSheetOwner($this->timesheetableType, $this->timesheetableId);
        // $user = json_encode($owner);
        $htmlUserRender = Blade::render("<x-renderer.avatar-user uid='$owner->id'></x-renderer.avatar-user>") ?? '';
        $js = "<script>let sheetOwnerId=" . $owner->id . ";</script>";

        return "$js<x-renderer.card title='TimeSheet Owner'>$htmlUserRender</x-renderer.card>";
    }
}
