<?php

namespace App\Http\Controllers\Entities\ZZTraitApi;

use App\Events\CreatedDocumentEvent2;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityFieldHandler2;
use App\Http\Services\LoggerForTimelineService;
use App\Models\Hr_timesheet_worker;
use App\Models\Hr_timesheet_worker_line;
use App\Models\Site_daily_assignment_line;
use App\Models\User_team_site;
use App\Models\User_team_tsht;
use App\Utils\Constant;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateTimeConcern;
use App\Utils\Support\Json\SuperProps;
use App\Utils\System\Api\ResponseObject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait TraitCreateNewShort
{
	use TraitEntityFieldHandler2;
	use TraitFailObject;

	public function createNewShort(Request $request)
	{
		try {
			$params = $request->input();
			$message = 'Create new short successfully';

			$inserted = $this->modelPath::create($params);

			return ResponseObject::responseSuccess(
				$inserted,
				[$params],
				$message,
			);
		} catch (\Throwable $th) {
			return $this->getFailObject($th);
		}
	}
}
