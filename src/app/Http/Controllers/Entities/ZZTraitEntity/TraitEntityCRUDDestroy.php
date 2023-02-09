<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use Illuminate\Http\Request;

trait TraitEntityCRUDDestroy
{
    function destroy(Request $request, $id)
    {
        $modelPath = $this->data;
        $item = $modelPath::find($id);
        $item->delete();
        // dd("Destroying", $request, $id,);
    }
}
