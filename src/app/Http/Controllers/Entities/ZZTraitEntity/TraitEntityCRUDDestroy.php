<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use Illuminate\Http\Request;

trait TraitEntityCRUDDestroy
{
    function destroy(Request $request, $id)
    {
        $modelPath = $this->data;
        $item = $modelPath::find($id);
        return $item->delete();
    }
}
