<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Entities\EntityCRUDController;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;
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
        $viewRender = $id ? "profile" : "me";
        $readOnly = $id ? true : false;
        $id = $id ?? CurrentUser::id();
        return $this->edit($request, $id, $viewRender,$title, $topTitle, $readOnly);
    }
}
