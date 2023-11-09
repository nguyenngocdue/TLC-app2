<?php

namespace App\Http\Controllers\Reports;

trait TraitConversionFieldNameGhgReport
{
    public function convertNames(&$dataSource){
		//Remove numbers in front of names
		$fieldNames = ['ghgtmpl_name', 'ghg_name', 'ghg_sheet_name'];
		foreach ($dataSource as &$item) {
			foreach ($fieldNames as $field){
				if(isset($item->$field)) {
					$indexDot1 = strpos($item->$field, '.')+1;
					$item->$field = trim(substr($item->$field, $indexDot1));
				}
			}
		}
		return $dataSource;
	}
}
