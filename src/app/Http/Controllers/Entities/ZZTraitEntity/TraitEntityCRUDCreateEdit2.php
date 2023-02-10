<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Models\Attachment;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Json\DefaultValues;
use App\Utils\Support\Json\Props;
use Database\Seeders\FieldSeeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait TraitEntityCRUDCreateEdit2
{
	use TraitEntityListenDataSource;

	public function create()
	{
		$props = $this->getCreateEditProps();
		$values =  (object) $this->loadValueOfOrphanAttachments($props);
		// dump($props);
		return view('dashboards.pages.entity-create-edit', [
			'props' => $props,
			'defaultValues' => DefaultValues::getAllOf($this->type),
			'type' => $this->type,
			'action' => __FUNCTION__,
			'modelPath' => $this->data,
			'values' => $values,
			'title' => "Add New",
			'topTitle' => CurrentRoute::getTitleOf($this->type),
			'listenerDataSource' => $this->renderListenDataSource(),
			'listeners' => $this->getListeners(),
			'filters' => $this->getFilters(),
		]);
	}

	public function edit($id)
	{
		$props = $this->getCreateEditProps();
		$values = (object) $this->loadValueOfOracyPropsAndAttachments($id, $props);
		return view('dashboards.pages.entity-create-edit', [
			'props' => $props,
			'defaultValues' => DefaultValues::getAllOf($this->type),
			'values' => $values,
			'type' => Str::plural($this->type),
			'action' => __FUNCTION__,
			'modelPath' => $this->data,
			'title' => "Edit",
			'topTitle' => CurrentRoute::getTitleOf($this->type),
			'listenerDataSource' => $this->renderListenDataSource(),
			'listeners' => $this->getListeners(),
			'filters' => $this->getFilters(),
		]);
	}

	private function getCreateEditProps()
	{
		$props = Props::getAllOf($this->type);
		$result = array_filter($props, fn ($prop) => $prop['hidden_edit'] !== 'true');
		return $result;
	}

	private function loadValueOfOrphanAttachments($props)
	{
		$fieldIds = [];
		$uid = CurrentUser::get()->id;
		foreach ($props as $prop) {
			if ($prop['control'] === 'attachment') {
				$fieldName = $prop['column_name'];
				$fieldId = FieldSeeder::getIdFromFieldName($fieldName);
				$fieldIds[] = $fieldId;
			}
		}
		// dump("SELECT all attachments with field_id IN (" . join(",", $fieldIds) . ") and owner_id=$uid");
		$query = Attachment::whereIn('category', $fieldIds)
			->where('owner_id', $uid)
			->where('object_id', NULL)
			->with('getCategory')
			->get();

		$result = [];
		$categoryNames = [];
		foreach ($query as $attachmentItem) {
			$categoryName = $attachmentItem->getRelations()['getCategory']->name;
			$attachmentItem->isOrphan = true;
			$categoryNames[] = $categoryName;
			$result[$categoryName][] = $attachmentItem;
		}
		$categoryNames = (array_unique($categoryNames));
		foreach ($categoryNames as $categoryName) {
			$result[$categoryName] = new Collection($result[$categoryName]);
		}
		// $result =  $result->toArray();
		// dump($result);
		return $result;
	}

	private function loadValueOfOracyPropsAndAttachments($id, $props)
	{
		$orphanAttachments = $this->loadValueOfOrphanAttachments($props);
		// dump($orphanAttachments);
		$original = $this->data::findOrFail($id);
		$values = $original->getOriginal();
		// dump($values);
		foreach ($props as $prop) {
			$name = $prop['column_name'];
			if ($prop['control'] === 'checkbox' || $prop['control'] === 'dropdown_multi') {
				$field_name = substr($name, 0, strlen($name) - 2); //Remove parenthesis()
				$values[$name] = json_encode($original->getCheckedByField($field_name)->pluck('id')->toArray());
			}
			if ($prop['control'] === 'attachment') {
				$attachmentsOfThisItem = $original->{$name};
				// dump($attachmentsOfThisItem);
				if (isset($orphanAttachments[$name])) {
					$attachmentsOfThisItem =  $attachmentsOfThisItem->merge($orphanAttachments[$name]);
				}
				$values[$name] =  $attachmentsOfThisItem;
				// dump("Adding $name to value");
				// dump($values);
			}
		}
		// dump($values);
		// $result = (object) $values;
		return $values;
	}
}
