<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Reports\Report_ParentDocument2Controller;
use App\Http\Controllers\Reports\TraitForwardModeReport;
use App\Http\Controllers\Reports\TraitGenerateValuesFromParamsReport;
use App\Http\Controllers\Reports\TraitParamsSettingReport;
use App\Models\Ghg_cat;
use App\Models\Ghg_metric_type;
use App\Models\Ghg_metric_type_1;
use App\Models\Ghg_metric_type_2;
use App\Models\Term;
use App\Utils\Support\ArrayReport;
use App\Utils\Support\DateReport;
use App\Utils\Support\NumberReport;
use App\Utils\Support\Report;
use Illuminate\Support\Facades\DB;

class Ghg_sheet_080 extends Report_ParentDocument2Controller
{

	protected $viewName = 'document-ghg-sheet-080';
	protected $mode = '080';









	protected function getDefaultValueParams($params, $request)
	{
		$params['year'] = [2022, 2023, (int)date('Y')];
		$params['children_mode'] = 'filter_by_year';
		return $params;
	}

	public function getParamColumns($dataSource, $modeType)
	{
		return [
			[
				'title' => 'Year',
				'dataIndex' => 'year',
				'multiple' => true,
			],
		];
	}

	public function getTableColumns($params, $dataSource)
	{
		return
			[
				[]
			];
	}



	public function getDataSource($params)
	{
		$instance1 = new Ghg_sheet_080_dataSource1();

		$dataEnergy = $instance1->generateDataSource($params);

		$instance = new Ghg_sheet_080_dataSource();
		$data3Scopes = $instance->divide3Scopes($params);

		// Accident data source
		$data3Accidents = $instance->divide3Accidents($params);

		// Total direct employees
		$dataDirectEmployees = $instance->divideTotalDirectEmployees($params);

		// Human Capital Gender Diversity - New Employees
		$dataNewEmployees = $instance->divideNewEmployees($params);
		// dd($dataNewEmployees);

		// Departed Employee
		$dataDepartedEmployees = $instance->divideDepartedEmployees($params);

		// Hours Worked per Full-time
		$dataHoursWorked = $instance1->getDataDepartedEmployees($params);


		$dataSource = array_merge(
			$dataEnergy,
			$data3Scopes,
			$data3Accidents,
			$dataDirectEmployees,
			$dataNewEmployees,
			$dataDepartedEmployees,
			$dataHoursWorked
		);

		return $dataSource;
	}
}
