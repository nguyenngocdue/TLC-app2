<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Providers\Support\TraitSupportPermissionGate;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

trait TraitEntityCRUDDestroy
{
    use TraitSupportPermissionGate;
    function destroy($id)
    {
        //check permission using gate
        $theLine = $this->checkPermissionUsingGate($id, 'delete');
        try {
            $theLine->delete();
            return ResponseObject::responseSuccess(
                null,
                [],
                "Delete document successfully!",
            );
        } catch (\Throwable $th) {
            return ResponseObject::responseFail(
                "Delete document fail!",
            );
        }
    }
}
