<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\ClassList;
use App\Utils\Support\CurrentRoute;
use Illuminate\Support\Str;

trait TraitEntityCRUDShowProps
{
	use TraitGetOptionPrint;
	public function showProps($id, $trashed)
	{

		$typePlural = Str::plural($this->type);
		$valueOptionPrint = $this->getValueOptionPrint();
		$item = $this->modelPath::find($id);
		$params =  [
			'type' => $this->type,
			'typePlural' => $typePlural,
			'id' => $id,
			'classListOptionPrint' => ClassList::DROPDOWN,
			'valueOptionPrint' => $valueOptionPrint,
			'layout' => $this->getLayoutPrint($valueOptionPrint, 'props'),
			'modelPath' => $this->modelPath,
			'trashed' => $trashed,
			'topTitle' => CurrentRoute::getTitleOf($this->type),
			'item' => $item,
		];
		return view('dashboards.pages.entity-show-props', $params);
	}
}
