<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Providers\Support\TraitSupportPermissionGate;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

trait TraitEntityCRUDDestroy
{
    use TraitSupportPermissionGate;
    public function destroy($id)
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
    public function destroyMultiple(Request $request)
    {
        try {

            $strIds = $request->ids;
            $ids = explode(',', $strIds) ?? [];
            $arrFail = $this->checkPermissionUsingGateForDeleteMultiple($ids, 'delete');
            $arrDelete = array_diff($ids, $arrFail);
            $this->data::whereIn('id', $arrDelete)->delete();
            return ResponseObject::responseSuccess(
                $arrDelete,
                [$arrFail],
                "Delete document successfully!",
            );
        } catch (\Throwable $th) {
            return ResponseObject::responseFail(
                "Delete document fail!",
            );
        }
    }
}
