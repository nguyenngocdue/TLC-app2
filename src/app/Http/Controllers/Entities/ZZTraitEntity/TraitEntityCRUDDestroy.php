<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Providers\Support\TraitSupportPermissionGate;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait TraitEntityCRUDDestroy
{
    use TraitSupportPermissionGate;
    public function destroyMultiple(Request $request, $hardDelete = false)
    {
        try {
            $strIds = $request->ids;
            // Log::info("Deleting " . $strIds);
            $ids = explode(',', $strIds) ?? [];
            $arrFail = $this->checkPermissionUsingGateForDeleteMultiple($ids, 'delete');
            // Log::info("Arr Fail " . join(",", $arrFail));
            $arrDelete = array_diff($ids, $arrFail);
            if ($hardDelete) {
                $this->modelPath::whereIn('id', $arrDelete)->forceDelete();
            } else {
                $this->modelPath::whereIn('id', $arrDelete)->delete();
            }
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
            $this->modelPath::withTrashed()->whereIn('id', $arrRestore)->restore();
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
