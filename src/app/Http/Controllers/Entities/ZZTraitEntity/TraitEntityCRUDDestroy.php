<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Providers\Support\TraitSupportPermissionGate;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Json\SuperWorkflows;
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
                "Deleted document successfully!",
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
            //$roleSet = CurrentUser::getRoleSet();
            //dd(SuperWorkflows::getFor($this->type, $roleSet));
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
    public function restore($id)
    {
        $theLine = $this->checkPermissionUsingGate($id, 'delete', true);
        try {
            $theLine->restore();
            return ResponseObject::responseSuccess(
                null,
                [],
                "Restore document successfully!",
            );
        } catch (\Throwable $th) {
            return ResponseObject::responseFail(
                "Restore document fail!",
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
            //$roleSet = CurrentUser::getRoleSet();
            //dd(SuperWorkflows::getFor($this->type, $roleSet));
            $this->data::withTrashed()->whereIn('id', $arrRestore)->restore();
            return ResponseObject::responseSuccess(
                $arrRestore,
                [$arrFail],
                "Restore document successfully!",
            );
        } catch (\Throwable $th) {
            return ResponseObject::responseFail(
                "Restore document fail!",
            );
        }
    }
}
