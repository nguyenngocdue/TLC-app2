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
		$tableBluePrint = $this->makeTableBluePrint();
		$tableToLoadDataSource = [...array_values($tableBluePrint), $this->type];
		return view('dashboards.pages.entity-create-edit', [
			'props' => $props,
			'defaultValues' => DefaultValues::getAllOf($this->type),
			'type' => $this->type,
			'action' => __FUNCTION__,
			'modelPath' => $this->data,
			'values' => $values,
			'title' => "Add New",
			'topTitle' => CurrentRoute::getTitleOf($this->type),
			'listenerDataSource' => $this->renderListenDataSource($tableToLoadDataSource),
			'listeners2' => $this->getListeners2($this->type),
			'filters2' => $this->getFilters2($this->type),
			'listeners4' => $this->getListeners4($tableBluePrint),
			'filters4' => $this->getFilters4($tableBluePrint),
		]);
	}

	public function edit($id)
	{
		$props = $this->getCreateEditProps();
		$values = (object) $this->loadValueOfOracyPropsAndAttachments($id, $props);
		$tableBluePrint = $this->makeTableBluePrint();
		$tableToLoadDataSource = [...array_values($tableBluePrint), $this->type];
		return view('dashboards.pages.entity-create-edit', [
			'props' => $props,
			'defaultValues' => DefaultValues::getAllOf($this->type),
			'values' => $values,
			'type' => Str::plural($this->type),
			'action' => __FUNCTION__,
			'modelPath' => $this->data,
			'title' => "Edit",
			'topTitle' => CurrentRoute::getTitleOf($this->type),
			'listenerDataSource' => $this->renderListenDataSource($tableToLoadDataSource),
			'listeners2' => $this->getListeners2($this->type),
			'filters2' => $this->getFilters2($this->type),
			'listeners4' => $this->getListeners4($tableBluePrint),
			'filters4' => $this->getFilters4($tableBluePrint),
		]);
	}

	private function getCreateEditProps()
	{
		$props = Props::getAllOf($this->type);
		$result = array_filter($props, fn ($prop) => $prop['hidden_edit'] !== 'true');
		return $result;
	}

	private function makeTableBluePrint()
	{
		$props = $this->getCreateEditProps();
		$props = array_values(array_filter($props, fn ($prop) => $prop['control'] == 'relationship_renderer'));
		$result = [];
		foreach ($props as $index => $prop) {
			$table01Name = "table" . str_pad($index + 1, 2, 0, STR_PAD_LEFT);
			$name = $prop['name'];
			// dump($this->superProps['props'][$name]);
			$result[$table01Name] = Str::singular($this->superProps['props'][$name]['relationships']['table']);
		}
		// dump($result);
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
