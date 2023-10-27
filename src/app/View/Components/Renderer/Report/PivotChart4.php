<?php

namespace App\View\Components\Renderer\Report;

use App\Http\Controllers\Reports\Widgets\WidgetGroup;
use App\Http\Controllers\Workflow\LibWidgets;
use App\Utils\Support\Report;
use App\Utils\Support\StringReport;
use Illuminate\View\Component;

class PivotChart4 extends Component
{
	public function __construct(
		private $data = [],
	) {
	}
	public function render()
	{
		return view("components.renderer.report.pivot-chart4", [
			'dataSource' => $this->data,
		]);
	}
}
