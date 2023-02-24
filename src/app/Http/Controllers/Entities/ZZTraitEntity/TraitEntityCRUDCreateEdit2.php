<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Models\Attachment;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateTimeConcern;
use App\Utils\Support\Json\DefaultValues;
use App\Utils\Support\Json\Props;
use App\Utils\Support\Json\Realtimes;
use App\Utils\Support\JsonControls;
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
			'item' => (object)[],
			'defaultValues' => DefaultValues::getAllOf($this->type),
			'realtimes' => Realtimes::getAllOf($this->type),
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
		// dump(SuperProps::getFor($this->type));
		$props = $this->getCreateEditProps();
		$original = $this->data::findOrFail($id);
		$values = (object) $this->loadValueOfOracyPropsAndAttachments($original, $props);
		$tableBluePrint = $this->makeTableBluePrint();
		$tableToLoadDataSource = [...array_values($tableBluePrint), $this->type];
		return view('dashboards.pages.entity-create-edit', [
			'props' => $props,
			'item' => $original,
			'defaultValues' => DefaultValues::getAllOf($this->type),
			'realtimes' => Realtimes::getAllOf($this->type),
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
		// dump($this->superProps);
		foreach ($props as $index => $prop) {
			$table01Name = "table" . str_pad($index + 1, 2, 0, STR_PAD_LEFT);
			$name = $prop['name'];
			// dump($name);
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

	private function loadValueOfOracyPropsAndAttachments($original, $props)
	{
		$orphanAttachments = $this->loadValueOfOrphanAttachments($props);
		// dump($orphanAttachments);		
		$values = $original->getOriginal();
		foreach ($props as $prop) {
			$name = $prop['column_name'];
			if (in_array($prop['control'], JsonControls::getDateTimeControls())) {
				$values[$name] = DateTimeConcern::convertForLoading($prop['control'], $values[$name]);
				continue;
			}
			switch ($prop['control']) {
				case 'checkbox':
				case 'dropdown_multi':
					$field_name = substr($name, 0, strlen($name) - 2); //Remove parenthesis()
					$values[$name] = json_encode($original->getCheckedByField($field_name)->pluck('id')->toArray());
					break;
				case 'attachment':
					$attachmentsOfThisItem = $original->{$name};
					if (isset($orphanAttachments[$name])) {
						$attachmentsOfThisItem =  $attachmentsOfThisItem->merge($orphanAttachments[$name]);
					}
					$values[$name] =  $attachmentsOfThisItem;
					break;
				default:
					break;
			}
		}
		// dump($values);
		// $result = (object) $values;
		return $values;
	}
}
