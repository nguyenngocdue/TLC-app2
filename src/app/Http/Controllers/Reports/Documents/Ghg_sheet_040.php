<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Reports\Report_ParentDocument2Controller;
use App\Http\Controllers\Reports\Reports\Ghg_sheet_dataSource;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitParamsSettingReport;
use App\Utils\Support\Report;
use App\View\Components\Renderer\Report\TraitCreateDataSourceWidget2;
use App\View\Components\Renderer\Report\TraitParamsInManageWidget;

class Ghg_sheet_040 extends Report_ParentDocument2Controller
{

	use TraitForwardModeReport;
	use TraitParamsSettingReport;
	use TraitParamsInManageWidget;
	use TraitCreateDataSourceWidget2;

	protected $viewName = 'document-ghg-sheet-040';
	protected $year = '2023';
	protected $mode = '020';
	protected $type = 'ghg_sheets';
	protected $optionPrint = "landscape";

	private function makeDataWidget($keyData, $dataSource, $dataManage, $params){
		$params['report_name'] = 'document-ghg_sheet_040';
		$paramMeta = $this->makeParamsInManageWidgets($params)[$keyData]['param_meta'];
		$result = $this->createDataSets($paramMeta, $dataSource, $dataManage);
        return $result;
    }

	private function getTargetEmission($dataSource){
		// dump($dataSource);
		foreach ($dataSource as  &$items){
			$valOfYear = (float)str_replace(',', '', $items->year);
			if(isset($items->scope_name) && $items->scope_name === 'Scope 1'){
				$items->year = (float)str_replace(',', '',number_format(($valOfYear/100)*70,2));
			}
			elseif(isset($items->scope_name) && $items->scope_name === 'Scope 2' && $items->ghg_tmpl_id === 5){
				$items->year = (float)str_replace(',', '',number_format(($valOfYear/100)*65,2));
			}
			elseif(isset($items->scope_name) && $items->scope_name === 'Scope 3' && $items->ghg_tmpl_id === 6){
				$items->year = (float)str_replace(',', '',number_format(($valOfYear/100)*80,2));
			}
			elseif(isset($items->scope_name) && $items->scope_name === 'Scope 3'){
				$items->year = (float)str_replace(',', '',number_format(($valOfYear/100)*70,2));
			}
		}
		return $dataSource;
	}

	public function getDataSource($params)
	{
		$yearParams = $params;
		$yearParams['year'] = $yearParams['year'] - 1;
		unset($yearParams['only_month']);
		unset($yearParams['quarter_time']);
		$primaryData = (new Ghg_sheet_dataSource())->getDataSource($yearParams);
		return collect($primaryData);
	}

	public function changeDataSource($dataSource, $params)
    {
		$currentDataSource = (new Ghg_sheet_dataSource())->getDataSource($params);
		$targetEmissionData = Report::convertToType($this->getTargetEmission($dataSource)->toArray());
		$currentPeriodData =  Report::convertToType($currentDataSource);
		// dd($emissionAllYearData, $targetEmissionData,$currentPeriodData, $params );
		
		// year is a fix value for left chart
		$newParams = $params;
		unset($newParams['only_month']);
		unset($newParams['quarter_time']);
		unset($newParams['half_year']);
		$emissionAllYearData =(new Ghg_sheet_dataSource())->getDataSource($newParams);
		$emissionAllYearData =  Report::convertToType($emissionAllYearData);

		$groupData = [
			'ghgrp_basin_production_and_emissions_all_year' =>[
				'meta_bar' => $emissionAllYearData],
				'ghgrp_basin_production_and_emissions_by_months' => [
					'meta_bar' => $currentPeriodData,
					'meta_bar2' => $targetEmissionData,
					]
				];

		// dd($groupData, $dataSource);

		$output = [];
		$params['report_name'] = 'document-ghg_sheet_040';
		$dataOfManageWidget = $this->makeParamsInManageWidgets($params);
		$arrayMax  = []; // to set width of X Axis
		$tempScaleMaxX = 0;
		foreach ($groupData as $keyData => $data) {
			foreach($dataOfManageWidget as $keyInManage => $dataManage){
				if($keyData === $keyInManage){
					$dataWidgets = $this->makeDataWidget($keyData, $data, $dataManage, $params);
					$output[$keyData] = $dataWidgets;
					if($dataWidgets['tempScaleMaxX'] > $tempScaleMaxX){
						$tempScaleMaxX = $dataWidgets['tempScaleMaxX'];
					}
				}
			}
			$arrayMax[$keyData] = $tempScaleMaxX;
		}
		
		// set max scale for dimension in data widgets
		foreach ($output as $keyData => &$data){
			$data['dimensions']['scaleMaxX'] = $arrayMax[$keyData];
			$data['dimensions']['scaleMaxY'] = $arrayMax[$keyData];
		}

		// Table of contents
		// $tableOfContent = [
		// 	'co2_emission_report' => 'Co2 Emission Report',
		// 	'methodology' => 'Methodology',

		// ]

        $result['dataWidgets'] = $output;


        return $result;
    }


	protected function getDefaultValueParams($params, $request)
	{
		$params['year'] = $this->year;
		return $params;
	}

	public function getParamColumns($dataSource, $modeType)
	{
		return [
			[
				'title' => 'Year',
				'dataIndex' => 'year',
			],
			[
				'title' => 'Half Year',
				'dataIndex' => 'half_year',
				// 'multiple' => true,
				'hasListenTo' => true,
				'allowClear' => true,
			],
			[
				'title' => 'Quarter',
				'dataIndex' => 'quarter_time',
				'multiple' => true,
				'allowClear' => true,
			],
			[
				'title' => 'Month',
				'dataIndex' => 'only_month',
				'multiple' => true,
				'allowClear' => true,
			],
		];
	}
}
