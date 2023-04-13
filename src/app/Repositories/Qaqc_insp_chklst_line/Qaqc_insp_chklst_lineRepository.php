<?php

namespace App\Repositories\Qaqc_insp_chklst_line;

use App\Repositories\BaseRepository;
use App\Models\Qaqc_insp_chklst_line;

class ProductRepository extends BaseRepository implements Qaqc_insp_chklst_lineRepositoryInterface
{

    public function getModel()
    {
        return Qaqc_insp_chklst_line::class;
    }
    public function update($request, $id)
    {
        $qaqcInspChklstLine = $this->model->find($id);
        if (in_array($request->controlValueId, ['4', '8'])) {
            $valueOnHold = $request->valueOnHold;
        } else {
            $valueOnHold = null;
        }
        $qaqcInspChklstLine->update([
            'value' => $request->value,
            'value_on_hold' => $valueOnHold,
            'qaqc_insp_control_value_id' => $request->controlValueId,
            'inspector_id' => $request->value != null ? $request->ownerId : null,

        ]);
        return $qaqcInspChklstLine;
    }
    public function softDeletes($id)
    {
    }

    public function restore($id)
    {
        $result = $this->model->withTrashed()->find($id)->restore();
        return $result;
    }
    public function trash($request)
    {
        $result = $this->model->onlyTrashed()->get();
        return $result;
    }
}
