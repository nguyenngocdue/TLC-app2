<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\ClassList;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

trait TraitViewAllPrint
{
    public function print(Request $request)
    {
        $dataSource = $request->except(['_token', '_method', '_entity', 'action', 'per_page']);
        if (empty($dataSource)) abort(404, "No document was selected to print.");

        $typePlural = Str::plural($this->type);
        $valueOptionPrint = $this->getValueOptionPrint();
        $params =  [
            'type' => $this->type,
            'typePlural' => $typePlural,
            'dataSource' => $dataSource,
            'classListOptionPrint' => ClassList::DROPDOWN,
            'valueOptionPrint' => $valueOptionPrint,
            'layout' => $this->getLayoutPrint($valueOptionPrint, 'props'),
            'modelPath' => $this->typeModel,
            'trashed' => false,
            'topTitle' => CurrentRoute::getTitleOf($this->type),
            'numberOfEmptyLines' => 5,
        ];
        return view('dashboards.pages.entity-show-template-print', $params);
    }
    public function showAll(Request $request){
        if(CurrentUser::isAdmin()){
            $dataSource = $this->typeModel::all()->where('status','active');
            $valueOptionPrint = $this->getValueOptionPrint();
            $params =  [
                'type' => $this->type,
                'typePlural' => Str::plural($this->type),
                'dataSource' => $dataSource,
                'classListOptionPrint' => ClassList::DROPDOWN,
                'valueOptionPrint' => $valueOptionPrint,
                'layout' => $this->getLayoutPrint($valueOptionPrint, 'props'),
                'modelPath' => $this->typeModel,
                'trashed' => false,
                'topTitle' => CurrentRoute::getTitleOf($this->type),
                'numberOfEmptyLines' => 5,
            ];
            return view('dashboards.pages.entity-show-props-all',$params);
        }
        
    }
}
