<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

trait TraitEntityDynamicType
{
    private function assignDynamicTypeViewAll()
    {
        $routeName = Request::route() ? Request::route()->getName() : "attachment.index";
        $tableName = substr($routeName, 0, strpos($routeName, "."));
        $singular = Str::singular($tableName);

        $this->type = $singular;
        $this->typeModel = Str::modelPathFrom($tableName);
        $this->permissionMiddleware = [
            'read' => "read-$tableName",
            'edit' => "read-$tableName|create-$tableName|edit-$tableName|edit-others-$tableName",
            'delete' => "read-$tableName|create-$tableName|edit-$tableName|edit-others-$tableName|delete-$tableName|delete-others-$tableName"
        ];
    }

    private function assignDynamicTypeCreateEdit()
    {
        $routeName = Request::route() ? Request::route()->getName() : "attachment.index";
        $tableName = substr($routeName, 0, strpos($routeName, "."));
        $singular = Str::singular($tableName);

        $this->type = $singular;
        $this->data = Str::modelPathFrom($tableName);
        $this->permissionMiddleware = [
            'read' => "read-$tableName",
            'edit' => "read-$tableName|create-$tableName|edit-$tableName|edit-others-$tableName",
            'delete' => "read-$tableName|create-$tableName|edit-$tableName|edit-others-$tableName|delete-$tableName|delete-others-$tableName"
        ];

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
    }

    private function assignDynamicTypeManual($tableName)
    {
        $tableName = Str::plural($tableName);
        $singular = Str::singular($tableName);

        $this->type = $singular;
        $this->data = Str::modelPathFrom($tableName);
        $this->permissionMiddleware = [
            'read' => "read-$tableName",
            'edit' => "read-$tableName|create-$tableName|edit-$tableName|edit-others-$tableName",
            'delete' => "read-$tableName|create-$tableName|edit-$tableName|edit-others-$tableName|delete-$tableName|delete-others-$tableName"
        ];

        // dump($this->type);
        // dump($this->typeModel);
        // dd();
    }
}
