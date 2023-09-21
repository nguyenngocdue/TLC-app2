<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Reports\Report_ParentController;
use App\Http\Controllers\Reports\TraitDynamicColumnsTableReport;
use App\Http\Controllers\UpdateUserSettings;
use App\Models\Attachment;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Report;

use function PHPUnit\Framework\isEmpty;

class Qaqc_insp_chklst_010 extends Report_ParentController
{
	use TraitDynamicColumnsTableReport;
	protected $viewName = 'document-qaqc-insp-chklst';

	// set default params's values 
	protected $checksheet_type_id = 1;
	protected  $sub_project_id = 82;
	protected  $prod_order_id = 238;
	protected  $check_sheet_id = 1;
	protected  $run_history_option_id = 1;

	public function getSqlStr($params)
	{
		$sql =  " SELECT
                        
                        tp.id AS template
                        ,l.value AS sign
                        ,l.value_comment AS value_comment
                        ,r.qaqc_insp_chklst_sht_id AS sheet_id
                        ,s.description AS sheet_name
                        ,r.id AS run_id
                        ,r.updated_at AS run_updated
                        ,r.description run_desc

                        ,l.id AS line_id
                        ,l.description AS line_description
                        ,l.name AS line_name

						,csh.id AS chklsts_id
						,csh.name AS chklsts_name
                        
                        ,cv.id AS control_value_id
                        ,cv.name AS control_value_name
                        ,g.description AS group_description
                        ,c1
                        ,c2
                        ,c3
                        ,c4";
		if (isset($params['prod_order_id'])) $sql .= "\n ,po.id AS po_id , po.name AS po_name , po.compliance_name AS compliance_name";
		if (isset($params['sub_project_id'])) $sql .= " \n ,sp.name AS sub_project_name, pj.name AS project_name ";
		$sql .= "\n FROM qaqc_insp_chklst_runs r
                    JOIN qaqc_insp_chklst_shts s ON r.qaqc_insp_chklst_sht_id = s.id #AND s.id = 44
                    JOIN qaqc_insp_chklsts csh ON csh.id = s.qaqc_insp_chklst_id
                    JOIN qaqc_insp_tmpls tp ON tp.id = csh.qaqc_insp_tmpl_id";
		if (isset($params['checksheet_type_id'])) $sql .= "\n AND tp.id = '{{checksheet_type_id}}'";
		$sql .= "\n JOIN qaqc_insp_chklst_run_lines l ON l.qaqc_insp_chklst_run_id = r.id
                    JOIN control_types ct ON ct.id = l.control_type_id";
		if (isset($params['prod_order_id']))  $sql .= "\nJOIN prod_orders po ON po.id = '{{prod_order_id}}'";
		else {
			$sql .= "\nJOIN prod_orders po ON po.id = 0";
		};
		if (isset($params['sub_project_id'])) $sql .= "\nJOIN sub_projects sp ON sp.id = po.sub_project_id
								 JOIN projects pj ON pj.id = sp.project_id";

		$sql .= "\nLEFT JOIN qaqc_insp_control_values cv ON l.qaqc_insp_control_value_id = cv.id
                JOIN qaqc_insp_groups g ON g.id = l.qaqc_insp_group_id 
                LEFT JOIN (
                    SELECT id as control_group_id
                    , REVERSE(SUBSTRING_INDEX(REVERSE(SUBSTRING_INDEX(cg.name, '|', 1)), '|', 1)) AS c1
                    , REVERSE(SUBSTRING_INDEX(REVERSE(SUBSTRING_INDEX(cg.name, '|', 2)), '|', 1)) AS c2
                    , REVERSE(SUBSTRING_INDEX(REVERSE(SUBSTRING_INDEX(cg.name, '|', 3)), '|', 1)) AS c3
                    , REVERSE(SUBSTRING_INDEX(REVERSE(SUBSTRING_INDEX(cg.name, '|', 4)), '|', 1)) AS c4
                    FROM qaqc_insp_control_groups AS cg
                            )  AS divide_control ON l.qaqc_insp_control_group_id = divide_control.control_group_id
            
                WHERE 1=1";
		if (isset($params['sub_project_id']))  $sql .= " \n AND po.sub_project_id = '{{sub_project_id}}' \n";
		if (isset($params['check_sheet_id'])) $sql .= " \n AND csh.id = '{{check_sheet_id}}'";
		$sql .= "\n ORDER BY line_name,  run_updated DESC ";
		return $sql;
	}

	public function getTableColumns($dataSource, $params)
	{
		return [
			[
				"title" => 'Description',
				"dataIndex" => "line_description",
				'width' => "10",
			],
			[
				"dataIndex" => "response_type",
				"align" => "center",
				'width' => "500"
			],
		];
	}

	protected function getParamColumns()
	{
		return [
			[
				'title' => 'Checksheet Type ',
				'dataIndex' => 'checksheet_type_id',
			],
			[
				'title' => 'Sub Project',
				'dataIndex' => 'sub_project_id',
			],
			[
				'title' => 'Production Order',
				'dataIndex' => 'prod_order_id',
			],
			[
				'title' => 'Checksheet',
				'dataIndex' => 'check_sheet_id',
			],
			[
				'title' => 'Run History Option',
				'dataIndex' => 'run_history_option_id',
			]
		];
	}


	protected function getPageParam($typeReport, $entity)
	{
		return 1000;
	}

	private function transformLines($groupByLinesDesc, $indexByLineIds, $params)
	{
		if (isset($params['run_option']) && !$params['run_option']) {
			array_walk($groupByLinesDesc, function ($ids, $desc) use (&$groupByLinesDesc) {
				if ($desc === 'TLC Inspector Name') {
					$groupByLinesDesc[$desc] = (array)$ids;
				} else {
					$groupByLinesDesc[$desc] = array_slice($ids, 0, 1, true);
				}
			});
		}
		foreach ($groupByLinesDesc as $ids) {
			array_walk($ids, function ($sheetId, $lineId) use (&$indexByLineIds) {
				$str = '';
				$item = $indexByLineIds[$lineId];
				if (!is_null($item['c1'])) {
					$str .= "<tr title='Chklst Run ID: {$item['run_id']}\nChklst Line ID: {$item['line_id']}' class=' bg-white border-b dark:bg-gray-800 dark:border-gray-700'>" . $this->createStrCheckboxHTML($item) . "</tr>";
					$str .= $this->createStrImage($item);
					$str .=  $this->createStrComment($item);
				} else {
					$str = "<h2>CSV</h2>";
				}
				$indexByLineIds[$lineId]['response_type'] = $str;
			});
		}
		return $indexByLineIds;
	}

	protected function createDataTableRuns($sheetGroup)
	{
		$data = [];
		foreach ($sheetGroup as $sheetId => $runs) {
			$groupDesc = Report::groupArrayByKey($runs, 'line_description');
			foreach ($groupDesc as $key => $value) {
				$responseTypes = array_column($value, 'response_type', 'line_id');
				$html = "<table class = 'w-full text-sm text-left text-gray-500 dark:text-gray-400'>" . "<tbody>" . implode(array_values($responseTypes)) . "</tbody>" . "</table>";
				$x = reset($value);
				$x['response_type'] = $html;
				$groupDesc[$key] = $x;
			}
			$data[$sheetId] = array_values($groupDesc);
		}
		return $data;
	}

	protected function enrichDataSource($dataSource, $params)
	{
		$isNullParams = Report::isNullParams($params);
		if ($isNullParams) return collect([]);

		$groupByChklsts = Report::groupArrayByKey($dataSource, 'chklsts_id');
		$chcklstIds = array_keys($groupByChklsts);

		// group lines into chcklst_id
		array_walk($chcklstIds, function ($value) use (&$groupByChklsts) {
			$groupByChklsts[$value] =  Report::assignKeyByKey($groupByChklsts[$value], 'line_id');
		});
		//add "response_type" field into lines
		array_walk($groupByChklsts, function ($value, $key) use (&$groupByChklsts, $params) {
			$groupByLinesDesc = Report::groupArrayByKey2($value, 'line_description', 'line_id', 'sheet_id');
			// unique run for  "TLC Inspector Name" line
			$groupByLinesDesc['TLC Inspector Name'] = array_unique($groupByLinesDesc['TLC Inspector Name']);
			$groupByChklsts[$key] = $this->transformLines($groupByLinesDesc, $value, $params);
		});
		//group lines into each sheet
		$sheetGroup = [];
		array_walk($groupByChklsts, function ($value, $key) use (&$sheetGroup) {
			$sheetGroup[$key] = Report::groupArrayByKey($value, 'sheet_id');
		});
		// combine "response_type" for each line
		$data = [];
		array_walk($sheetGroup, function ($value, $key) use (&$data) {
			$data += $this->createDataTableRuns($value);
		});
		ksort($data);
		return collect($data);
	}

	protected function getAttachment($object_type, $object_id)
	{
		if (!$object_type && !$object_id) return [];
		$uid = CurrentUser::get()->id;
		$query = Attachment::whereIn('object_type', [$object_type])
			->where('owner_id', $uid)
			->where('object_id', $object_id)
			->get();
		return $query->ToArray();
	}

	protected function createStrHtmlImage($item)
	{
		$object_type = "App\Models\qaqc_insp_chklst_run_line";
		$attachment = $this->getAttachment($object_type, $item['line_id']);
		if (empty($attachment)) return '';
		$path = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/';
		$strCenter = "";
		foreach ($attachment as  $att) {
			$url_thumbnail = $path . $att["url_thumbnail"];
			$url_media = $path . $att["url_media"];
			$fileName = $att["filename"];
			$strCenter .= "
                            <div class='border-gray-300 relative h-full flex mx-1 flex-col items-center p-1 border rounded-lg  group/item overflow-hidden bg-inherit'>
                                <img class='' src='$url_thumbnail' alt='$fileName' />
                                <div class='invisible flex justify-center hover:bg-[#00000080] group-hover/item:visible before:absolute before:-inset-1  before:bg-[#00000080]'>
                                        <a title='$fileName' href='$url_media' target='_blank' class='hover:underline text-white hover:text-blue-500 px-2 absolute top-[50%] left-[50%] translate-x-[-50%] translate-y-[-50%] text-lg text-center w-full'>
                                            <span class='text-sm'><i class='fa-sharp fa-solid fa-eye text-2xl '></i></span>
                                        </a>
                                </div>
                            </div>";
		};
		$strHead = "<div class='flex flex-col container mx-auto w-full' >
                    <div class='grid grid-cols-5 lg:gap-3 md:gap-2 sm:gap-1 '>";
		$strTail = "</div></div>";
		return $strHead . $strCenter . $strTail;
	}

	private function createStrCheckboxHTML($item)
	{
		$circleIcon = "<i class='fa-thin fa-circle px-2'></i>";
		$checkedIcon = "<i class='fa-solid fa-circle-check px-2'></i>";
		$arrayControl = ['c1' => $item['c1'], 'c2' => $item['c2'], 'c3' => $item['c3'], 'c4' => $item['c4']];
		$str = "";
		foreach ($arrayControl as $col => $value) {
			if ($item['control_value_name'] === $item[$col]) {
				$str .= '<td class="border" style="width:50px">' . $checkedIcon . $value . '</td>';
			} else {
				$str .=  '<td class="border" style="width:50px">' . $circleIcon . $value . '</td>';
			}
		};
		$runUpdated = $this->createStrDateTime($item);
		$runDesc =  env('APP_ENV')  === '_local' ? '<td class="border" style="width:10px">' . $item['run_desc'] . ":" . "</td>" : "";
		$line_id =  env('APP_ENV')  === '_local' ? '<td class="border" style="width:10px">' . 'line_id:' .  $item['line_id'] . '</td>' : "";
		$longStr = $runDesc . $line_id . $str . $runUpdated;
		return $longStr;
	}

	private function createStrImage($item)
	{
		if (!strlen($this->createStrHtmlImage($item))) return '';
		$td = '<td class="border" colspan=5 style="width:190px">'  . $this->createStrHtmlImage($item) . '</td>';
		return "<tr  class=' bg-white border-b dark:bg-gray-800 dark:border-gray-700'>" . $td . "</tr>";
	}

	private function createStrComment($item)
	{
		if (is_null($item['value_comment'])) return "<tr> </tr>";
		$td = "<td class='border p-3' colspan = 5 style='width:190px'>{$item['value_comment']}</td>";
		return "<tr class=' bg-white border-b dark:bg-gray-800 dark:border-gray-700'>" . $td . "</tr>";
	}

	private function createStrDateTime($item)
	{
		$runUpdated = '<td class="border pl-2" style="width:80px" > Date: ' . str_replace(" ", "</br> Time: ", $item['run_updated']) . "</td>";
		return $runUpdated;
	}

	protected function getSheets($dataSource)
	{
		$items = Report::convertToType($dataSource);
		$sheets = array_values(array_map(function ($item) {
			$x = isset(reset($item)['sheet_name']);
			if ($x) {
				$name = array_pop($item)['sheet_name'];
				$str = "<a href='#$name' class='text-blue-600'>$name</a>";
				return  ["sheet_name" =>  $str];
			} else return [];
		}, $items));
		return $sheets;
	}

	protected function getDefaultValueParams($params, $request)
	{
		$x = 'sub_project_id';
		$y = 'prod_order_id';
		$z = 'check_sheet_id';
		$l = 'checksheet_type_id';
		$m = 'run_history_option_id';

		$params[$x] = $this->sub_project_id;
		$params[$y] = $this->prod_order_id;
		$params[$z] = $this->check_sheet_id;
		$params[$l] = $this->checksheet_type_id;
		$params[$m] = $this->run_history_option_id;
		// dd($params);
		return $params;
	}

	protected function forwardToMode($request, $params)
	{
		$input = $request->input();
		$isFormType = isset($input['form_type']);
		if ($isFormType && $input['form_type'] === 'updateParamsReport') {
			// check_sheet listen to prod_order
			if (!$input['prod_order_id']) {
				$input['check_sheet_id'] = "0";
				$request->replace($input);
			}
			(new UpdateUserSettings())($request);
		}
		return redirect($request->getPathInfo());
	}
}
