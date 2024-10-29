<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\Support\CurrentRoute;
use Illuminate\Support\Facades\Log;

trait TraitEntityCRUDShowChklstSht2
{
    private $nominatedListFn = "signature_qaqc_chklst_3rd_party";

    public function showChklstSht2($id, $trashed)
    {
        $entity = $trashed ? ($this->modelPath)::withTrashed()->findOrFail($id) : ($this->modelPath)::findOrFail($id);

        return view("dashboards.pages.entity-show-chklst-sht", [
            'topTitle' => CurrentRoute::getTitleOf($this->type),
            'modelPath' => $this->modelPath,
            'sheet' => $entity,
        ]);
    }
}
