<style type="text/css">.tg {
  border-collapse: collapse;
  border-spacing: 0;
}

.tg td {
  border-color: black;
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg th {
  border-color: black;
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: normal;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg .tg-qjmw {
  background-color: #9c9191;
  border-color: #000000;
  color: #ffffff;
  font-size: 16px;
  font-weight: bold;
  text-align: left;
  vertical-align: top
}

.tg .tg-npz6 {
  background-color: #c0c0c0;
  border-color: #000000;
  text-align: center;
  vertical-align: top
}

.tg .tg-ggjs {
  background-color: #9b9b9b;
  border-color: #000000;
  color: #ffffff;
  text-align: left;
  vertical-align: top
}

.tg .tg-f7v4 {
  border-color: #000000;
  text-align: left;
  vertical-align: top
}

.tg .tg-u8ky {
  background-color: #9b9b9b;
  border-color: #000000;
  color: #ffffff;
  font-size: 22px;
  font-weight: bold;
  text-align: left;
  vertical-align: top
}

.tg .tg-73oq {
  border-color: #000000;
  text-align: left;
  vertical-align: top
}

.tg .tg-mcqj {
  border-color: #000000;
  font-weight: bold;
  text-align: left;
  vertical-align: top
}

.tg .tg-jpxn {
  border-color: #000000;
  font-size: 16px;
  font-weight: bold;
  text-align: left;
  vertical-align: top
}

.tg .tg-cscf {
  background-color: #c0c0c0;
  border-color: #000000;
  font-weight: bold;
  text-align: left;
  vertical-align: top
}

</style>

@php
	$years = is_array($params['year']) ? $params['year'] : [$params['year']];
	$timeCategory =  $params['children_mode'];
	$titleOfTime = array_values(App\Utils\Support\DateReport::generateTimeRanges($years));
@endphp

<div class="p-2 border">
	<div class="flex flex-row justify-between p-4 ">
		<h4 class="font-bold text-2xl">ENVIRONMENTAL, SOCIAL AND GOVERNANCE KEY PERFORMANCE INDICATORS</h4>
		<h2 class="font-bold text-5xl">ADM CAPITAL</h2>
	</div>
	<div class="p-6 ">
		<table class="tg" style="undefined;table-layout: fixed; width: 1965px">
				<colgroup>
				<col style="width: 550.75px">
				<col style="width: 24.75px">
				<col style="width: 170.75px">
				<col style="width: 361.75px">
				<col style="width: 90.75px">
				<col style="width: 119.75px">
				<col style="width: 117.75px">

				{{-- Width of columns --}}
				@if ($timeCategory === 'filter_by_half_year')
					@for ($i = 0; $i < count($titleOfTime); $i++)
						<col style="width: 121.75px">
					@endfor
				@else
					@foreach ($years as $year)
						<col style="width: 121.75px">
					@endforeach
				@endif

				{{-- @dump($titleOfTime) --}}

				<col style="width: 151.75px">
				<col style="width: 19.75px">
				</colgroup>
				{{-- <thead>
				<tr>
					<th class="tg-ggjs" colspan="{{ 6 + count($titleOfTime)}}">ENVIRONMENTAL, SOCIAL AND GOVERNANCE KEY PERFORMANCE INDICATORS</th>
					<th class="tg-u8ky" colspan="1">ADM CAPITAL</th>
				</tr>
				</thead> --}}
				<tbody>
				<tr>
					<td class="tg-mcqj">Deal Name</td>
					<td class="tg-73oq"></td>
					<td class="tg-mcqj">Project Lotus</td>
					<td class="tg-mcqj"></td>
					<td class="tg-73oq"><span style="font-weight:700;font-style:normal">Period Start</span></td>
					<td class="tg-mcqj"></td>
					@if ($timeCategory === 'filter_by_half_year')
						@foreach ( $titleOfTime as $value)
							<td class="tg-mcqj">{{$value['begin_date']}}</td>
						@endforeach
					@else
						@foreach ( $years as $value)
							<td class="tg-mcqj">{{$value}}-01-31</td>
						@endforeach
					@endif
					<td class="tg-mcqj" colspan="{{$timeCategory === 'filter_by_year' ? 6 : 3}}"></td>
				</tr>
				<tr>
					<td class="tg-mcqj">Data Submission Date</td>
					<td class="tg-73oq"></td>
					<td class="tg-73oq"></td>
					<td class="tg-mcqj"></td>
					<td class="tg-73oq"><span style="font-weight:700;font-style:normal">Period End</span></td>
					<td class="tg-mcqj" ></td>
					@if ($timeCategory === 'filter_by_half_year')
						@foreach ( $titleOfTime as $value)
							<td class="tg-mcqj">{{$value['end_date']}}</td>
						@endforeach
					@else
						@foreach ( $years as $value)
							<td class="tg-mcqj">{{$value}}-12-31</td>
						@endforeach
					@endif
					<td class="tg-mcqj" colspan="{{$timeCategory === 'filter_by_year' ? 6 : 3}}"></td>
				</tr>
				@php
					$colSpanOfHead = $timeCategory === 'filter_by_year' ? count($years) + 7 + 3 : count($years)*2 + 7 + 3;
				@endphp

				<tr>
					<td class="tg-73oq" colspan="{{ $colSpanOfHead }}">Please fill in the BLUE...</td>
				</tr>
				<tr>
					<td class="tg-73oq" colspan="{{ $colSpanOfHead }}">Please also fill in the blank BLUE CELLS</td>
				</tr>
				<tr>
					<td class="tg-73oq" colspan="{{ $colSpanOfHead }}">Please confirm/revise</td>
				</tr>


				<tr>
					<td class="tg-qjmw">KPI  Topic</td>
					<td class="tg-qjmw" colspan="4">KPI Metric</td>
					<td class="tg-qjmw" colspan="1">Unit</td>

					{{-- Title range of time --}}
					@if ($timeCategory === 'filter_by_half_year')
						@foreach ( $titleOfTime as $items)
							<td class="tg-qjmw">{{$items['text']}}</td>
						@endforeach
					@else
						@foreach ( $years as $year)
							<td class="tg-qjmw">{{$year}}</td>
						@endforeach
					@endif
					<td class="tg-qjmw" colspan="3"></td>
				</tr>
				<tr>
					<td class="tg-jpxn" colspan="6">ENVIROMENT</td>
					<td class="tg-f7v4" colspan="{{ $timeCategory === 'filter_by_year' ?  count($years) :  count($titleOfTime)}}"><span style="font-weight:bold">Reporting Boundary:</span> Vietnam faciticies (TF1, TF2 and TF3)</td>
					<td class="tg-73oq" colspan="{{$timeCategory === 'filter_by_year' ? count($years) : count($years) - 2 + 3}}"></td>
				</tr>

				<tr>
					<td class="tg-cscf">Energy</td>
					<td class="tg-f7v4" colspan="5">Direct Fuel Consumption</td>
					@include('components.reports.value-by-category')
					<td class="tg-73oq" colspan="3"></td>
				</tr>

				<tr>
					<td class="tg-f7v4" rowspan="10"></td>
					<td class="tg-f7v4" rowspan="5"></td>
					<td class="tg-f7v4" colspan="3">from Renewable Energy Source (ie: bioenergy)</td>
					<td class="tg-f7v4">Please specify</td>
					@include('components.reports.value-by-category')
					<td class="tg-73oq" colspan="3"></td>
				</tr>


				<tr>
					<td class="tg-f7v4" colspan="3">from Oil</td>
					<td class="tg-f7v4">Litres</td>
					@include('components.reports.value-by-category', ['fieldName' => 'from_oil', 'timeCategory' => $timeCategory])
					<td class="tg-73oq" colspan="3"></td>
				</tr>


				<tr>
					<td class="tg-f7v4" colspan="3">from Natural Gas</td>
					<td class="tg-f7v4">BTU</td>
					@include('components.reports.value-by-category', ['fieldName' => 'from_natural_gas', 'timeCategory' => $timeCategory])
					<td class="tg-73oq" colspan="3"></td>
				</tr>


				<tr>
					<td class="tg-f7v4" colspan="3">from Goal</td>
					<td class="tg-f7v4">Litres</td>
					@include('components.reports.value-by-category')
					<td class="tg-73oq" colspan="3"></td>
				</tr>

				<tr>
					<td class="tg-f7v4" colspan="3">from Other (please specify non-renewable fuel type)</td>
					<td class="tg-f7v4">kg</td>
					@include('components.reports.value-by-category', ['fieldName' => 'from_other_please_specify_non_renewable_fuel_type', 'timeCategory' => $timeCategory])
					<td class="tg-73oq" colspan="3"></td>
				</tr>

				<tr>
					<td class="tg-f7v4" colspan="4">Indirect Energy Consumption</td>
					<td class="tg-f7v4">kWh</td>
					@include('components.reports.value-by-category')
					<td class="tg-73oq" colspan="3"></td>
				</tr>


				<tr>
					<td class="tg-f7v4" rowspan="3"></td>
					<td class="tg-f7v4" colspan="3">Electricity Consumption from Renewable Energy Sources</td>
					<td class="tg-f7v4">KWh</td>
					@include('components.reports.value-by-category', ['fieldName' => 'electricity_consumption_from_renewable_energy_sources', 'timeCategory' => $timeCategory])
					<td class="tg-73oq" colspan="3"></td>
				</tr>
					<tr>
					<td class="tg-f7v4" colspan="3">Electricity Consumption from Non-Renewable Energy Sources</td>
					<td class="tg-f7v4">KWh</td>
					@include('components.reports.value-by-category', ['fieldName' => 'electricity_consumption_from_non_renewable_energy_sources', 'timeCategory' => $timeCategory])
					<td class="tg-73oq" colspan="3"></td>
				</tr>
				

				<tr>
					<td class="tg-f7v4" colspan="3">Other indirect energy consumption (e.g. district heating, cooding, purchased steam)</td>
					<td class="tg-f7v4">kWh</td>
					@include('components.reports.value-by-category')
					<td class="tg-73oq" colspan="3"></td>
				</tr>

				<tr>
					<td class="tg-f7v4" colspan="4">Total Renewable Energy Generated</td>
					<td class="tg-f7v4">kWh</td>
					@include('components.reports.value-by-category')
					<td class="tg-73oq" colspan="3"></td>
				</tr>

				<tr>
					<td class="tg-cscf">Waste</td>
					<td class="tg-f7v4" colspan="4">Total Solid Waste Generated</td>
					<td class="tg-f7v4">kg</td>
					@include('components.reports.value-by-category', ['showNa' => false])
					<td class="tg-73oq" colspan="3"></td>
				</tr>

				<tr>
					<td class="tg-f7v4" rowspan="3"></td>
					<td class="tg-f7v4" rowspan="3"></td>
					<td class="tg-f7v4" colspan="3">Waste Diverted from Disposal (Reused/Recycled/Recorverd)</td>
					<td class="tg-f7v4">kg</td>
					@include('components.reports.value-by-category', ['fieldName' => 'waste_diverted_from_disposal', 'timeCategory' => $timeCategory])
					<td class="tg-73oq" colspan="3"></td>
				</tr>

				<tr>
					<td class="tg-f7v4" colspan="3">Hazardous Waste Generated</td>
					<td class="tg-f7v4">kg</td>
					@include('components.reports.value-by-category')
					<td class="tg-73oq" colspan="3"></td>
				</tr>

				<tr>
					<td class="tg-f7v4"></td>
					<td class="tg-f7v4" colspan="2">Radicactive Waste Generated</td>
					<td class="tg-f7v4">kg</td>
					@include('components.reports.value-by-category')
					<td class="tg-73oq" colspan="3"></td>
				</tr>

				<tr>
					<td class="tg-cscf">Water</td>
					<td class="tg-f7v4" colspan="4">Total Water Consumption</td>
					<td class="tg-f7v4">m3</td>
					@include('components.reports.value-by-category')
					<td class="tg-73oq" colspan="3"></td>
				</tr>

				<tr>
					<td class="tg-f7v4"></td>
					<td class="tg-f7v4"></td>
					<td class="tg-f7v4" colspan="3">Water Consumption from Recycled And Reused Sources</td>
					<td class="tg-f7v4">m3</td>
					@include('components.reports.value-by-category', ['fieldName' => 'total_water_consumption', 'timeCategory' => $timeCategory])
					<td class="tg-73oq" colspan="3"></td>
				</tr>

				<tr>
					<td class="tg-cscf">GHG Emissions</td>
					<td class="tg-f7v4" colspan="4">Scope 1 GHG Emissions</td>
					<td class="tg-f7v4">tCO2e</td>
					@include('components.reports.value-by-category', ['fieldName' => 'scope_1_ghg_emissions', 'timeCategory' => $timeCategory])
					<td class="tg-73oq" colspan="3"></td>
				</tr>

				<tr>
					<td class="tg-f7v4" rowspan="2"></td>
					<td class="tg-f7v4" colspan="4">Scope 2 GHG Emissions</td>
					<td class="tg-f7v4">tCO2e</td>
					@include('components.reports.value-by-category', ['fieldName' => 'scope_2_ghg_emissions', 'timeCategory' => $timeCategory])
					<td class="tg-73oq" colspan="3"></td>
				</tr>

				<tr>
					<td class="tg-f7v4" colspan="4">Scope 3 GHG Emissons</td>
					<td class="tg-f7v4">tCO2e</td>
					@include('components.reports.value-by-category', ['fieldName' => 'scope_3_ghg_emissions', 'timeCategory' => $timeCategory])
					<td class="tg-73oq" colspan="3"></td>
				</tr>

				<tr>
					<td class="tg-jpxn" colspan="6">SOCIAL</td>
					<td class="tg-f7v4" colspan="{{ $timeCategory === 'filter_by_year' ?  count($years) :  count($titleOfTime)}}"><span style="font-weight:bold">Reporting Boudary: </span>Vietnam facilities (TF1, TF2 and TF3)</td>
					<td class="tg-73oq" colspan="{{$timeCategory === 'filter_by_year' ? count($years) : count($years) - 2 + 3}}">ADM (20210108): Please confirm whether the social dât only covers those stated in green cell. ....</td>
				</tr>

				<tr>
					<td class="tg-cscf">Human Capital &amp; Gender Diversity</td>
					<td class="tg-f7v4" colspan="4">Total Direct Employees</td>
					<td class="tg-f7v4">Number</td>
					@include('components.reports.value-by-category', ['fieldName' => 'total_direct_employees', 'timeCategory' => $timeCategory])
					<td class="tg-73oq" colspan="3"></td>
				</tr>

				<tr>
					<td class="tg-f7v4" rowspan="19"></td>
					<td class="tg-f7v4"></td>
					<td class="tg-f7v4" colspan="3">Senior Employees (ie: managers, directors, supervisors)</td>
					<td class="tg-f7v4">Number</td>
					@include('components.reports.value-by-category', ['fieldName' => 'senior_employees', 'timeCategory' => $timeCategory])
					<td class="tg-73oq" colspan="3"></td>
				</tr>

				<tr>
					<td class="tg-f7v4" colspan="2"></td>
					<td class="tg-f7v4" colspan="2">Female</td>
					<td class="tg-f7v4">Number</td>
					@include('components.reports.value-by-category', ['fieldName' => 'senior_employees_female', 'timeCategory' => $timeCategory])
					<td class="tg-73oq" colspan="3"></td>
				</tr>
				<tr>
					<td class="tg-f7v4" colspan="2"></td>
					<td class="tg-f7v4" colspan="2">Male</td>
					<td class="tg-f7v4">Number</td>
					@include('components.reports.value-by-category', ['fieldName' => 'senior_employees_male', 'timeCategory' => $timeCategory])
					<td class="tg-73oq" colspan="3"></td>
				</tr>
				<tr>
					<td class="tg-f7v4"></td>
					<td class="tg-f7v4" colspan="3">Junior Employees</td>
					<td class="tg-f7v4">Number</td>
					@include('components.reports.value-by-category', ['fieldName' => 'junior_employees', 'timeCategory' => $timeCategory])
					<td class="tg-73oq" colspan="3"></td>
				</tr>
				<tr>
					<td class="tg-f7v4" colspan="2"></td>
					<td class="tg-f7v4" colspan="2">Female</td>
					<td class="tg-f7v4">Number</td>
					@include('components.reports.value-by-category', ['fieldName' => 'junior_employees_female', 'timeCategory' => $timeCategory])
					<td class="tg-73oq" colspan="3"></td>
				</tr>
				<tr>
					<td class="tg-f7v4" colspan="2"></td>
					<td class="tg-f7v4" colspan="2">Male</td>
					<td class="tg-f7v4">Number</td>
					@include('components.reports.value-by-category', ['fieldName' => 'junior_employees_male', 'timeCategory' => $timeCategory])
					<td class="tg-73oq" colspan="3"></td>
				</tr>
				<tr>
					<td class="tg-f7v4"></td>
					<td class="tg-f7v4" colspan="3">New Empoyees</td>
					<td class="tg-f7v4">Number</td>
					@include('components.reports.value-by-category', ['fieldName' => 'total_new_employees', 'timeCategory' => $timeCategory])
					<td class="tg-73oq" colspan="3"></td>
				</tr>
				<tr>
					<td class="tg-f7v4" colspan="2"></td>
					<td class="tg-f7v4" colspan="2">Female</td>
					<td class="tg-f7v4">Number</td>
					@include('components.reports.value-by-category', ['fieldName' => 'new_employees_female', 'timeCategory' => $timeCategory])
					<td class="tg-73oq" colspan="3"></td>
				</tr>
				<tr>
					<td class="tg-f7v4" colspan="2"></td>
					<td class="tg-f7v4" colspan="2">Male</td>
					<td class="tg-f7v4">Number</td>
					@include('components.reports.value-by-category', ['fieldName' => 'new_employees_male', 'timeCategory' => $timeCategory])
					<td class="tg-73oq" colspan="3"></td>
				</tr>
				<tr>
					<td class="tg-f7v4"></td>
					<td class="tg-f7v4" colspan="3">Departed Employees</td>
					<td class="tg-f7v4">Number</td>
					@include('components.reports.value-by-category', ['fieldName' => 'total_departed_employees', 'timeCategory' => $timeCategory])
					<td class="tg-73oq" colspan="3"></td>
				</tr>
				<tr>
					<td class="tg-f7v4" colspan="2"></td>
					<td class="tg-f7v4" colspan="2">Female</td>
					<td class="tg-f7v4">Number</td>
					@include('components.reports.value-by-category', ['fieldName' => 'departed_employees_female', 'timeCategory' => $timeCategory])
					<td class="tg-73oq" colspan="3"></td>
				</tr>
				<tr>
					<td class="tg-f7v4" colspan="2"></td>
					<td class="tg-f7v4" colspan="2">Male</td>
					<td class="tg-f7v4">Number</td>
					@include('components.reports.value-by-category', ['fieldName' => 'departed_employees_male', 'timeCategory' => $timeCategory])
					<td class="tg-73oq" colspan="3"></td>
				</tr>
				<tr>
					<td class="tg-f7v4"></td>
					<td class="tg-f7v4" colspan="3">Hours Worked per Full-time Employee within 6-month period</td>
					<td class="tg-f7v4">Hours/per person</td>
					@include('components.reports.value-by-category', ['fieldName' => 'hours_worked_employee', 'timeCategory' => $timeCategory])
					<td class="tg-73oq" colspan="3"></td>
				</tr>
				<tr>
					<td class="tg-f7v4" colspan="4">Part-time/Season Workers Employees</td>
					<td class="tg-f7v4">Number</td>
					@include('components.reports.value-by-category')
					<td class="tg-73oq" colspan="3"></td>
				</tr>
				<tr>
					<td class="tg-f7v4" colspan="2"></td>
					<td class="tg-f7v4" colspan="2">Female</td>
					<td class="tg-f7v4">Number</td>
					@include('components.reports.value-by-category')
					<td class="tg-73oq" colspan="3"></td>
				</tr>
				<tr>
					<td class="tg-f7v4" colspan="2"></td>
					<td class="tg-f7v4" colspan="2">Male</td>
					<td class="tg-f7v4">Number</td>
					@include('components.reports.value-by-category')
					<td class="tg-73oq" colspan="3"></td>
				</tr>
				<tr>
					<td class="tg-f7v4"></td>
					<td class="tg-f7v4" colspan="3">Total Hours Worked by Part-time/Seasonal Workers</td>
					<td class="tg-f7v4">Hours</td>
					@include('components.reports.value-by-category')
					<td class="tg-73oq" colspan="3"></td>
				</tr>
				<tr>
					<td class="tg-f7v4" colspan="4">On-site Workers Under a Contractor</td>
					<td class="tg-f7v4">Number</td>
					@include('components.reports.value-by-category')
					<td class="tg-73oq" colspan="3"></td>
				</tr>
				<tr>
					<td class="tg-f7v4" colspan="4">Employees under the age of 18</td>
					<td class="tg-f7v4">Number</td>
					@include('components.reports.value-by-category')
					<td class="tg-73oq" colspan="3"></td>
				</tr>
				<tr>
					<td class="tg-cscf">Training</td>
					<td class="tg-f7v4" colspan="4">Total Training Hours Attended (across All Employees)</td>
					<td class="tg-f7v4">Hours</td>
					@include('components.reports.value-by-category')
					<td class="tg-73oq" colspan="3"></td>
				</tr>
				<tr>
					<td class="tg-f7v4" rowspan="2"></td>
					<td class="tg-f7v4"></td>
					<td class="tg-f7v4" colspan="3">ESG -related Traning</td>
					<td class="tg-f7v4">Hours</td>
					@include('components.reports.value-by-category')
					<td class="tg-73oq" colspan="3"></td>
				</tr>
				<tr>
					<td class="tg-f7v4"></td>
					<td class="tg-f7v4" colspan="3">Trained Employees</td>
					<td class="tg-f7v4">Number</td>
					@include('components.reports.value-by-category')
					<td class="tg-73oq" colspan="3"></td>
				</tr>
				<tr>
					<td class="tg-cscf">Accidents</td>
					<td class="tg-f7v4" colspan="4">Occupational Accidents</td>
					<td class="tg-f7v4">Number</td>
					@include('components.reports.value-by-category', ['fieldName' => 'occupational_accidents', 'timeCategory' => $timeCategory])
					<td class="tg-73oq" colspan="3"></td>
				</tr>
				<tr>
					<td class="tg-f7v4" rowspan="2"></td>
					<td class="tg-f7v4" colspan="4">Occupational Near Misses</td>
					<td class="tg-f7v4">Number</td>
					@include('components.reports.value-by-category', ['fieldName' => 'occupational_near_miss', 'timeCategory' => $timeCategory])
					<td class="tg-73oq" colspan="3"></td>
				</tr>
				<tr>
					<td class="tg-f7v4" colspan="4">Total Lost Time Due to Injury, Accidents, Fatalities, or Illness</td>
					<td class="tg-f7v4">Hours</td>
					@include('components.reports.value-by-category', ['fieldName' => 'total_lost_time', 'timeCategory' => $timeCategory])
					<td class="tg-73oq" colspan="3"></td>
				</tr>
				<tr>
					<td class="tg-cscf">Grievances</td>
					<td class="tg-f7v4" colspan="4">Internal Grievances / Complaints Received</td>
					<td class="tg-f7v4">Number</td>
					@include('components.reports.value-by-category')
					<td class="tg-73oq" colspan="3"></td>
				</tr>
				<tr>
					<td class="tg-f7v4" rowspan="4"></td>
					<td class="tg-f7v4"></td>
					<td class="tg-f7v4" colspan="3">Incidents Related to Discrimination Received</td>
					<td class="tg-f7v4">Number</td>
					@include('components.reports.value-by-category')
					<td class="tg-73oq" colspan="3"></td>
				</tr>
				<tr>
					<td class="tg-f7v4"></td>
					<td class="tg-f7v4" colspan="3">Incidents Grievances / Complaints Resolved</td>
					<td class="tg-f7v4">Number</td>
					@include('components.reports.value-by-category')
					<td class="tg-73oq" colspan="3"></td>
				</tr>
				<tr>
					<td class="tg-f7v4" colspan="4">External Grievances / Complaints Received</td>
					<td class="tg-f7v4">Number</td>
					@include('components.reports.value-by-category')
					<td class="tg-73oq" colspan="3"></td>
				</tr>
				<tr>
					<td class="tg-f7v4"></td>
					<td class="tg-f7v4" colspan="3">External Grievances / Complaints Resolved</td>
					<td class="tg-f7v4">Number</td>
					@include('components.reports.value-by-category')
					<td class="tg-73oq" colspan="3"></td>
				</tr>
				<tr>
					<td class="tg-73oq" colspan=""></td>
				</tr>
				</tbody>
		</table>
	</div>
</div>