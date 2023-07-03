<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

trait TraitEntityDynamicType
{
    protected function makePermissionMiddleware($tableName)
    {
        return [
            'read' => "read-$tableName",
            'create' => "create-$tableName",
            'edit' => "edit-$tableName|edit-others-$tableName",
            'delete' => "delete-$tableName|delete-others-$tableName"
        ];
    }

    private function assignDynamicTypeViewAll()
    {
        $routeName = Request::route() ? Request::route()->getName() : "attachment.index";
        $tableName = substr($routeName, 0, strpos($routeName, "."));
        $singular = Str::singular($tableName);

        $this->type = $singular;
        $this->typeModel = Str::modelPathFrom($tableName);
        $this->permissionMiddleware = $this->makePermissionMiddleware($tableName);
        // $this->permissionMiddleware = [
        //     'read' => "read-$tableName",
        //     'create' => "create-$tableName",
        //     'edit' => "edit-$tableName|edit-others-$tableName",
        //     'delete' => "delete-$tableName|delete-others-$tableName"
        // ];
    }

    private function assignDynamicTypeViewAllQrList6()
    {
        $routeName = Request::route() ? Request::route()->getName() : "pj_modules_qr";
        $tableName = substr($routeName, 0, strrpos($routeName, "_"));
        $singular = Str::singular($tableName);

        $this->type = $singular;
        $this->typeModel = Str::modelPathFrom($tableName);
        $this->permissionMiddleware = $this->makePermissionMiddleware($tableName);
        // $this->permissionMiddleware = [
        //     'read' => "read-$tableName",
        //     'create' => "create-$tableName",
        //     'edit' => "edit-$tableName|edit-others-$tableName",
        //     'delete' => "delete-$tableName|delete-others-$tableName"
        // ];
    }

    protected function assignDynamicTypeCreateEdit()
    {
        $routeName = Request::route() ? Request::route()->getName() : "attachment.index";
        $tableName = substr($routeName, 0, strpos($routeName, "."));
        $singular = Str::singular($tableName);

        $this->type = $singular;
        $this->data = Str::modelPathFrom($tableName);
        $this->permissionMiddleware = $this->makePermissionMiddleware($tableName);
        // $this->permissionMiddleware = [
        //     'read' => "read-$tableName",
        //     'create' => "create-$tableName",
        //     'edit' => "edit-$tableName|edit-others-$tableName",
        //     'delete' => "delete-$tableName|delete-others-$tableName"
        // ];

        // dump($this->type);
        // dump($this->typeModel);
        // dd();
    }

    private function assignDynamicTypeCreateEditForApi()
    {
        $routeName = Request::route() ? Request::route()->getName() : "attachment.index";
        $tableName = substr($routeName, 0, strpos($routeName, "."));
        $singular = Str::singular($tableName);

        $this->type = $singular;
        $this->modelPath = Str::modelPathFrom($tableName);
        // $this->permissionMiddleware = [
        //     'read' => "read-$tableName",
        //     'edit' => "read-$tableName|create-$tableName|edit-$tableName|edit-others-$tableName",
        //     'delete' => "read-$tableName|create-$tableName|edit-$tableName|edit-others-$tableName|delete-$tableName|delete-others-$tableName"
        // ];

        // dump($this->type);
        // dump($this->typeModel);
        // dd();
    }

    private function assignDynamicTypeManageJson()
    {
        $routeName = Request::route() ? Request::route()->getName() : "attachment_prp";
        $tableName = substr($routeName, 0, strrpos($routeName, "_"));
        $singular = Str::singular($tableName);

        $this->type = $singular;
        $this->typeModel = Str::modelPathFrom($tableName);
        $tableName = Str::plural($tableName);
        $this->permissionMiddleware = $this->makePermissionMiddleware($tableName);
        // $this->permissionMiddleware = [
        //     'read' => "read-$tableName",
        //     'create' => "create-$tableName",
        //     'edit' => "edit-$tableName|edit-others-$tableName",
        //     'delete' => "delete-$tableName|delete-others-$tableName"
        // ];
    }

    private function assignDynamicTypeManual($tableName)
    {
        $tableName = Str::plural($tableName);
        $singular = Str::singular($tableName);

        $this->type = $singular;
        $this->data = Str::modelPathFrom($tableName);
        $this->permissionMiddleware = $this->makePermissionMiddleware($tableName);
        // $this->permissionMiddleware = [
        //     'read' => "read-$tableName",
        //     'create' => "create-$tableName",
        //     'edit' => "edit-$tableName|edit-others-$tableName",
        //     'delete' => "delete-$tableName|delete-others-$tableName"
        // ];

        // dump($this->type);
        // dump($this->typeModel);
        // dd();
    }
}
