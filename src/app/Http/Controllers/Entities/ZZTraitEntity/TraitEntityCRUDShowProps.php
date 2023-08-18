<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\ClassList;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Json\DefaultValues;
use App\Utils\Support\Json\SuperProps;
use App\Utils\Support\WorkflowFields;
use Illuminate\Support\Str;

trait TraitEntityCRUDShowProps
{
    use TraitGetOptionPrint;
	public function showProps($id, $trashed)
	{
		
		$typePlural =Str::plural($this->type);
		$valueOptionPrint = $this->getValueOptionPrint();
		$params =  [
			'type' => $this->type,
			'typePlural' => $typePlural,
			'id' => $id,
			'classListOptionPrint' => ClassList::DROPDOWN,
			'valueOptionPrint' => $valueOptionPrint,
			'layout' => $this->getLayoutPrint($valueOptionPrint),
			'modelPath' => $this->data,
			'trashed' => $trashed,
			'topTitle' => CurrentRoute::getTitleOf($this->type),
		];
		return view('dashboards.pages.entity-show-props', $params);
	}
}
