<?php

namespace App\Console\Commands\Traits;

use App\Models\Qaqc_insp_chklst_line;

trait CloneRunTrait
{
    /**
     * cloneSheet
     *
     * @param  mixed $modelRun Model Run need clone
     * @param  mixed $idSheet 
     * @return void
     */
    public function cloneRun($modelRun, $newModelRun = null, $idRun = null)
    {
        $qaqcInspTmplLines = $modelRun->getLines;
        if (count($qaqcInspTmplLines) > 0) {
            foreach ($qaqcInspTmplLines as $qaqcInspTmplLine) {
                Qaqc_insp_chklst_line::create([
                    'name' => $qaqcInspTmplLine->name,
                    'description' => $qaqcInspTmplLine->description,
                    'control_type_id' => $qaqcInspTmplLine->control_type_id,
                    'qaqc_insp_chklst_run_id' => $idRun ? $idRun : $newModelRun->id,
                    'qaqc_insp_group_id' => $qaqcInspTmplLine->qaqc_insp_group_id,
                    'qaqc_insp_control_value_id' => $newModelRun ? $qaqcInspTmplLine->qaqc_insp_control_value_id : null,
                    'qaqc_insp_control_group_id' => $qaqcInspTmplLine->qaqc_insp_control_group_id,
                    'value' => $newModelRun ? $qaqcInspTmplLine->value : null,
                ]);
            }
        }
    }
}
