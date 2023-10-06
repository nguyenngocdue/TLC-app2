<?php

namespace App\Http\Controllers\Entities\ZZTraitApi;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDStoreUpdate2;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait TraitKanbanUpdate
{
    use TraitEntityCRUDStoreUpdate2;

    function updateItemRenderProps(Request $request)
    {
        $input = $request->input();
        ['id' => $id] = $input;
        $props = $this->getProps1();

        $item = $this->modelPath::find($id);
        $item->update($input);
        $this->handleCheckboxAndDropdownMulti($request, $item, $props['oracy_prop']);
        return ResponseObject::responseSuccess([], [], "Updated");
    }
}
