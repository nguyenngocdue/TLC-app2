<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Entities\EntityCRUDController;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityListenDataSource;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitSupportEntityCRUDCreateEdit2;
use App\Http\Controllers\Workflow\LibApps;
use App\Models\User;
use App\Providers\Support\TraitSupportPermissionGate;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Json\DefaultValues;
use App\View\Components\Formula\All_DocId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ProfileController extends EntityCRUDController
{
    protected function assignDynamicTypeCreateEdit()
    {
        $this->type = 'users';
        $this->data = Str::modelPathFrom('users');
        $this->permissionMiddleware = $this->makePermissionMiddleware('users');
    }
    public function profile(Request $request, $id = null)
    {
        $title = "Profile";
        $topTitle = $id ? "Profile" : "Me";
        $readOnly = $id ? true : false;
        $id = $id ?? CurrentUser::id();
        return $this->edit($request, $id, $title, $topTitle, $readOnly);
    }
}
