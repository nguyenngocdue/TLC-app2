<?php

namespace App\View\Components\Renderer\Report;

use App\Http\Controllers\Workflow\LibWidgets;
use App\Utils\Support\StringReport;

trait TraitParamsInManageWidget
{
	private function makeObject($allWidgets, $params)
	{
		foreach($allWidgets as $key => &$widget) {
			$widget['dimensions'] = (array)json_decode($widget['dimensions']);
			$widget['params'] = StringReport::separateStringsByDot((array)json_decode($widget['params']));
			$widget['key_md5'] = md5($key);
			$widget['key_name'] = $key;
			$widget['param_filters'] = $params;
			$widget['option_print'] = $params['optionPrint'] ?? 'landscape';
		}
		return $allWidgets;
	}
    public function makeParamsInManageWidgets($params){

		$uri = $_SERVER['REQUEST_URI'];
        $reportName =last(explode('/',$uri));
        $allWidgets = LibWidgets::getAll();
        $widgetsOfReports = array_filter($allWidgets, function($widget) use($reportName) {
            return isset($widget['report_name']) ?  $widget['report_name'] === $reportName : false;
        });
		$paramInManage =  $this->makeObject($widgetsOfReports, $params);
		return $paramInManage;
	}

}
