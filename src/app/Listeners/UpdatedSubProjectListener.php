<?php

namespace App\Listeners;

use App\Events\UpdatedSubProjectEvent;
use App\Models\Qaqc_insp_chklst;
use App\Models\Sub_project;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class UpdatedSubProjectListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    function reloadExternalInspectors($subProjectId)
    {
        $allLists = Qaqc_insp_chklst::query()
            ->where("sub_project_id", $subProjectId)
            ->with(['getSheets'])
            ->get();

        $result = [];
        foreach ($allLists as $list) {
            $allSheets = $list->getSheets;
            foreach ($allSheets as $sheet) {
                $result[] = $sheet->getMonitors1()->pluck('id')->toArray();
            }
        }

        $result = array_values(array_unique(Arr::flatten($result)));

        $sp = Sub_project::find($subProjectId);
        $sp->syncCheck("getExternalInspectorsOfSubProject", \App\Models\User::class, $result);
    }

    public function handle(UpdatedSubProjectEvent $event)
    {
        $subProject = $event->book->sub_project_id;
        $this->reloadExternalInspectors($subProject);
    }
}
