<?php

namespace App\View\Components\Renderer\Report;

use App\Http\Controllers\Workflow\LibWidgets;
use App\Utils\Support\StringReport;

trait TraitParamsInManageWidget
{

    private static function parseArrayWithJson($str, $delimiter = ",")
    {
        $str = str_replace("\r\n", "", $str);
        $strings = explode($delimiter, $str);
        $strings = array_filter($strings, fn ($s) => $s);
        $result = [];
        foreach ($strings as $string) {
            if (str_contains($string, "{")) {
                [$key, $json] = explode("{", $string);
                $result[$key] = json_decode("{" . $json);
            }
        }
        return $result;
    }



	private function makeObject($allWidgets, $params)
	{
		foreach($allWidgets as $key => &$widget) {
			$widget['dimensions'] = (array)json_decode($widget['dimensions']);
			$widget['params'] = StringReport::separateStringsByDot((array)json_decode($widget['params']));
			$widget['line_series'] = self::parseArrayWithJson($widget['line_series'], ';');
			$widget['param_meta'] = self::parseArrayWithJson($widget['param_meta'], ';');
			$widget['key_md5'] = md5($key);
			$widget['key_name'] = $key;
			$widget['param_filters'] = $params;
			$widget['option_print'] = $params['optionPrint'] ?? 'landscape';
		}
		return $allWidgets;
	}
    public function makeParamsInManageWidgets($params){

		$uri = $_SERVER['REQUEST_URI'];
        $reportName = $params['report_name'] ?? last(explode('/',$uri));
        $allWidgets = LibWidgets::getAll();
        $widgetsOfReports = array_filter($allWidgets, function($widget) use($reportName) {
			return isset($widget['report_name']) && !$widget['hidden'] ?  $widget['report_name'] === $reportName : false;
        });
		$paramInManage =  $this->makeObject($widgetsOfReports, $params);
		// dd($paramInManage);
		return $paramInManage;
	}

}
