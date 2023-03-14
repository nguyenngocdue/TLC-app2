<?php

namespace App\Http\Controllers\Reports\Documents;

use App\Http\Controllers\Reports\Report_ParentController;
use App\Http\Controllers\Reports\TraitReport;
use App\Models\Attachment;
use App\Models\Prod_order;
use App\Models\Qaqc_insp_tmpl;
use App\Models\Sub_project;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Report;

class Qaqc_insp_chklst_010 extends Report_ParentController
{
	use TraitReport;
	protected $viewName = 'document-qaqc-insp-chklst';

	public function getSqlStr($modeParams)
	{
		$isCheck = isset($modeParams['prod_order_id']) && isset($modeParams['sub_project_id']);
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
                        
                        ,cv.id AS control_value_id
                        ,cv.name AS control_value_name
                        ,g.description AS group_description
                        ,c1
                        ,c2
                        ,c3
                        ,c4";
		if ($isCheck) $sql .= "\n ,po.id AS po_id , po.name AS po_name";
		if ($isCheck) $sql .= " \n ,sp.name AS project_name";
		$sql .= "\n FROM qaqc_insp_chklst_runs r
                    JOIN qaqc_insp_chklst_shts s ON r.qaqc_insp_chklst_sht_id = s.id
                    JOIN qaqc_insp_chklsts csh ON csh.id = s.qaqc_insp_chklst_id
                    JOIN qaqc_insp_tmpls tp ON tp.id = csh.qaqc_insp_tmpl_id";
		if (isset($modeParams['qaqc_insp_tmpl_id'])) $sql .= "\n AND tp.id = '{{qaqc_insp_tmpl_id}}'";
		$sql .= "\n JOIN qaqc_insp_chklst_lines l ON l.qaqc_insp_chklst_run_id = r.id
                    JOIN control_types ct ON ct.id = l.control_type_id";
		if ($isCheck) $sql .= "\nJOIN prod_orders po ON po.id = '{{prod_order_id}}'";
		if ($isCheck) $sql .= "\nJOIN sub_projects sp ON sp.id = po.sub_project_id";

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
		if ($isCheck) $sql .= " \n AND po.sub_project_id = '{{sub_project_id}}' \n";
		// if (isset($modeParams['chklsts'])) $sql .= " \n AND csh.id = '{{chklsts}}' \n";
		$sql .= "\n ORDER BY line_name,  run_updated DESC ";
		return $sql;
	}

	public function getTableColumns($dataSource = [])
	{
		return [
			[
				"title" => 'Description',
				"dataIndex" => "line_description",
				'width' => "10"
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
				'title' => 'Sub Project',
				'dataIndex' => 'sub_project_id',
				// 'allowClear' => true
			],
			[
				'title' => 'Production Order',
				'dataIndex' => 'prod_order_id',
				// 'allowClear' => true
			],
			[
				'title' => 'Checklist Type',
				'dataIndex' => 'qaqc_insp_tmpl_id',
				// 'allowClear' => true
			],
			[
				'dataIndex' => 'run_option'
			]
		];
	}

	public function getDataForModeControl($dataSource = [])
	{
		$subProjects = ['sub_project_id' => Sub_project::get()->pluck('name', 'id')->toArray()];
		$prod_orders  = ['prod_order_id' =>  Prod_order::get()->pluck('name', 'id')->toArray()];
		$insp_tmpls = ['qaqc_insp_tmpl_id' => Qaqc_insp_tmpl::get()->pluck('name', 'id')->toArray()];
		$run_option = ['run_option' => ['View only last run', 'View all runs']];
		return array_merge($subProjects, $prod_orders, $insp_tmpls, $run_option);
	}


	protected function enrichDataSource($dataSource, $modeParams)
	{
		$isNullParams = $this->isNullModeParams($modeParams);
		if ($isNullParams) return collect([]);

		$lines =  [];
		foreach ($dataSource as $item) {
			if (isset($item->line_id)) {
				$lines[$item->line_id] = (array)$item;
			}
		}
		$descIdLines = [];
		array_walk($lines, function ($value, $key) use (&$descIdLines) {
			$descIdLines[$value['line_description']][] = $key;
		});
		// Reduce the number of lines from URL request 
		if (isset($modeParams['run_option']) && !$modeParams['run_option']) {
			$descIdLines = array_map(fn ($item) => array_splice($item, 0, 1), $descIdLines);
		}



		// Reduce the number of lines from URL request
		// if (isset($modeParams['run_option']) && !$modeParams['run_option']) {
		// 	array_walk($descIdLines, function ($value, $key) use ($lines, &$descIdLines) {
		// 		$maxRunId = array_splice($value, 0, 1);
		// 		$line = $lines[$maxRunId[0]];
		// 		if (!is_null($line['c1'])) {
		// 			// dd("123", $descIdLines[$key], $maxRunId);
		// 			$descIdLines[$key] = $maxRunId;
		// 		} else {
		// 			// dump($maxRunId);
		// 			$descIdLines[$key] = $value;
		// 		}
		// 	});
		// }

		// dd($descIdLines);

		$arrayHtml = [];
		foreach ($descIdLines as $ids) {
			$str = '';
			foreach ($ids as $id) {
				$item = $lines[$id];
				// dd($item['c1']);
				if (!is_null($item['c1'])) {
					$str .= "<tr class=' bg-white border-b dark:bg-gray-800 dark:border-gray-700'>" . $this->createLongStrHTML($item) . "</tr>";
					$str .= $this->createStrImage($item);
					$str .=  $this->createStrComment($item);
					$arrayHtml[$id] = "<table class = 'w-full text-sm text-left text-gray-500 dark:text-gray-400'>" . "<tbody>" . $str . "</tbody>" . "</table>";
				} else {
					// $str .=  $item['sign'];
					$arrayHtml[$id] = "<h2>CSV</h2>";
				}
			}
		}
		// dd($arrayHtml);
		$arrayMaxRun = [];
		foreach ($lines as $id => $value) {
			if (isset($arrayHtml[$id])) {
				$value['response_type'] = $arrayHtml[$id];
				$arrayMaxRun[$id] = $value;
			}
		}
		$sheetGroup = Report::groupArrayByKey($arrayMaxRun, 'sheet_id');
		// dd($sheetGroup);

		$sheetId_Desc = [];
		foreach ($sheetGroup as $sheetId => $value) {
			$groupDesc = Report::groupArrayByKey($value, 'line_description');
			foreach ($groupDesc as $key => $value) {
				$groupDesc[$key] = array_pop($value);
			}
			$sheetId_Desc[$sheetId] = $groupDesc;
		}
		$data = [];
		foreach ($sheetId_Desc as $sheetId => $values) {
			$data[$sheetId] = array_values($values);
		}
		ksort($data);
		// dd("123", $dataSource);
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
		$object_type = "App\Models\qaqc_insp_chklst_line";
		$attachment = $this->getAttachment($object_type, $item['line_id']);
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

	private function createLongStrHTML($item)
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

		$runDesc =  env('APP_ENV')  === 'local' ? '<td class="border" style="width:10px">' . $item['run_desc'] . ":" . "</td>" : "";
		$line_id =  env('APP_ENV')  === 'local' ? '<td class="border" style="width:10px">' . 'line_id:' .  $item['line_id'] . '</td>' : "";

		$longStr = $runDesc . $line_id . $str . $runUpdated;
		return $longStr;
	}

	private function createStrImage($item)
	{
		$td = '<td class="border" colspan=5 style="width:190px">' . '<div class="">' . $this->createStrHtmlImage($item) . '</div>' . '</td>';
		return "<tr class=' bg-white border-b dark:bg-gray-800 dark:border-gray-700'>" . $td . "</tr>";
	}

	private function createStrComment($item)
	{
		if (is_null($item['value_comment'])) return "<tr> </tr>";
		$td = "<td class='border p-3' colspan = 5 style='width:190px'>{{$item['value_comment']}}</td>";
		return "<tr class=' bg-white border-b dark:bg-gray-800 dark:border-gray-700'>" . $td . "</tr>";
	}

	private function createStrDateTime($item)
	{
		$runUpdated = '<td class="border pl-2" style="width:80px" > Date: ' . str_replace(" ", "</br> Time: ", $item['run_updated']) . "</td>";
		return $runUpdated;
	}

	protected function getSheets($dataSource)
	{
		$items = Report::pressArrayTypeAllItems($dataSource);
		// dd($items);
		$sheets = array_values(array_map(function ($item) {
			$x = isset(array_pop($item)['sheet_name']);
			if ($x) {
				$name = array_pop($item)['sheet_name'];
				$str = "<a href='#$name'>$name</a>";
				return  ["sheet_name" =>  $str];
			} else return [];
		}, $items));
		// dd($sheets);
		return $sheets;
	}

	protected function setDefaultValueModeParams($modeParams)
	{
		$x = 'sub_project_id';
		$y = 'prod_order_id';
		if (!isset($modeParams[$x]) || empty($modeParams)) {
			return [$x => 21, $y => 82];
		}
		return $modeParams;
	}
}
