<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Events\EntityCreatedEvent;
use App\Helpers\Helper;
use App\Notifications\CreateNewNotification;
use App\Notifications\EditNotification;
use App\Utils\Support\Json\DefaultValues;
use App\Utils\Support\Json\Props;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

trait TraitEntityCRUDStoreUpdate2
{
	public function store(Request $request)
	{
		dump($request);
		dd("Storing ...");
	}

	public function update(Request $request, $id)
	{
		dump($request);
		dd("Updating ...");
	}
}
