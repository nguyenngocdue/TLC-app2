<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Providers\Support\TraitSupportPermissionGate;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;

trait TraitEntityCRUDDestroy
{
    use TraitSupportPermissionGate;
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
        } catch (\Exception $e) {
            return ResponseObject::responseFail(
                $e->getMessage(),
            );
        }
    }

    public function restoreMultiple(Request $request)
    {
        try {
            $strIds = $request->ids;
            $ids = explode(',', $strIds) ?? [];
            $arrFail = $this->checkPermissionUsingGateForDeleteMultiple($ids, 'delete', true);
            $arrRestore = array_diff($ids, $arrFail);
            $this->data::withTrashed()->whereIn('id', $arrRestore)->restore();
            return ResponseObject::responseSuccess(
                $arrRestore,
                [$arrFail],
                "Restore document successfully!",
            );
        } catch (\Exception $e) {
            return ResponseObject::responseFail(
                $e->getMessage(),
            );
        }
    }
}
