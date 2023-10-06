<?php

namespace App\Http\Controllers\Entities\ZZTraitApi;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDStoreUpdate2;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;

trait TraitKanbanUpdate
{
    use TraitKanbanItemRenderer;
    use TraitEntityCRUDStoreUpdate2;

    function updateItemRenderProps(Request $request)
    {
        $input = $request->input();
        ['id' => $id, 'groupWidth' => $groupWidth] = $input;
        $props = $this->getProps1();

        $item = $this->modelPath::find($id);
        $item->update($input);
        $this->handleCheckboxAndDropdownMulti($request, $item, $props['oracy_prop']);

        $renderer = $this->renderKanbanItem($item, $groupWidth);

        return ResponseObject::responseSuccess(['renderer' => $renderer], [], "Updated");
    }
}
