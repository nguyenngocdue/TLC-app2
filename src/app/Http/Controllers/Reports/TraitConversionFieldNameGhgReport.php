<?php

namespace App\Http\Controllers\Reports;

trait TraitConversionFieldNameGhgReport
{

	private function removeNumbersAndChars($inputString) {
		$resultString = preg_replace("/[^a-zA-Z\s]/", '', $inputString);
		return $resultString;
	}
    public function convertNames(&$dataSource){
		//Remove numbers in front of names
		$fieldNames = ['ghgtmpl_name', 'ghg_name', 'ghg_sheet_name'];
		foreach ($dataSource as &$item) {
			foreach ($fieldNames as $field){
				if(isset($item->$field)) {
					// $indexDot1 = strpos($item->$field, '.')+1;
					$item->$field = trim($this->removeNumbersAndChars($item->$field));
				}
			}
		}
		return $dataSource;
	}
}
