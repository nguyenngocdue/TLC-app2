<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Entities\EntityCRUDController;
use App\Http\Controllers\Workflow\LibProfileFields;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Json\SuperProps;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProfileController extends EntityCRUDController
{
    protected function getEditTopTitle()
    {
        return "Profile";
    }

    protected function getEditTitle()
    {
        $id = CurrentRoute::getEntityId($this->type);
        return $id ? "Profile" : "Me";
    }

    private function overrideStandardProfileFields($allProps)
    {
        $libProfileFields = LibProfileFields::getAll();
        $viewRender = 'me';
        if ($viewRender) {
            foreach ($allProps as $propKey => &$value) {
                foreach ($libProfileFields as $field) {
                    if ($field['name'] == $propKey) {
                        $value['hidden_edit'] = isset($field[$viewRender . '_hidden']) ? $field[$viewRender . '_hidden'] : '';
                        $value['read_only'] = isset($field[$viewRender . '_readonly']) ? $field[$viewRender . '_readonly'] : '';
                    }
                }
            }
        }
        return $allProps;
    }

    protected function getSuperProps()
    {
        $result = SuperProps::getFor($this->type);
        $result['props'] = $this->overrideStandardProfileFields($result['props']);
        return $result;
    }

    protected function assignDynamicTypeCreateEdit()
    {
        $this->type = 'users';
        $this->data = Str::modelPathFrom('users');
        $this->permissionMiddleware = $this->makePermissionMiddleware('users');
    }

    public function profile(Request $request, $id = null)
    {
        $viewRender = $id ? "profile" : "me";
        $readOnly = $id ? true : false;
        $id = $id ?? CurrentUser::id();
        return $this->edit($request, $id, $viewRender, $readOnly);
    }
}
