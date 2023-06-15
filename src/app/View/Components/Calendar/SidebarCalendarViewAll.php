<?php

namespace App\View\Components\Calendar;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllFunctions;
use App\Models\User;
use App\Providers\Support\TraitSupportPermissionGate;
use Illuminate\View\Component;

class SidebarCalendarViewAll extends Component
{
    use TraitSupportPermissionGate;
    use TraitViewAllFunctions;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $type,
        private $typeModel,
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
        $type = $this->type;
        $treeIds = $this->getTreeOwnerIds(auth()->user());
        $ids = $this->getListOwnerIds(auth()->user());
        $users = User::whereIn('id', $ids)->where('show_on_beta', 0)->where('resigned', 0);
        $ids = $users->pluck('id')->toArray();
        $dataSource = ($this->typeModel)::where('owner_id', $ids)->get();
        $result = [];
        return view('components.calendar.sidebar-calendar-view-all', [
            'users' => $users->get(),
            'dataSource' => $result,
        ]);
    }
    private function treeDataSource($tree, $dataSource, $type)
    {
        dd($tree);
        foreach ($tree as $value) {
            dump($value);
            if (isset($value->children)) {
                dd(123);
                $result = $this->treeDataSource($value->children, $dataSource, $type);
            } else {
                $result[$value->id]['id'] = $value->id;
                $result[$value->id]['total_pending_approval'] = $dataSource->where('owner_id', $value->id)->where('status', 'pending_approval')->count();
                $result[$value->id]['href'] = "?view_type=calendar&action=updateViewAllCalendar&_entity=$type&owner_id%5B%5D=$value->id";
            }
        }
        return $result;
    }
}
