		// get controls are switch - set its value is 0
		$witchControls = [];
		foreach ($props as $key => $value) {
		if ($value['control'] === "switch") {
		array_push($witchControls, $value['column_name']);
		}
		}
		foreach ($witchControls as $key => $value) {
		if (isset($dataInput[$value]) === false) {
		$dataInput[$value] = "0";
		}
		}
		// dd($dataInput);