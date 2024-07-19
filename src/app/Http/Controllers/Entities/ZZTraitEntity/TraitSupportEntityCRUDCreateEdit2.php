<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Models\Attachment;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Json\SuperProps;
use Database\Seeders\FieldSeeder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait TraitSupportEntityCRUDCreateEdit2
{
    private function hashDryRunToken($id)
    {
        $user = auth()->user();
        $userId = $user->id;
        $userPassword = $user->password;
        $value = $this->type . $id . $userId . $userPassword;
        return $value;
    }
    private function checkDryRunToken($dryRunTokenRequest, $value)
    {
        if ($dryRunTokenRequest && !Hash::check($value, $dryRunTokenRequest)) abort(403, 'Your dryrun token is invalid');
    }

    protected function getSuperProps()
    {
        $result = SuperProps::getFor($this->type);
        return $result;
    }

    private function getDefaultValue($props)
    {
        $result = [];
        foreach ($props as $prop) {
            $dv = $prop['default-values']['default_value'] ?? false;
            if ($dv !== "") $result[$prop['column_name']] = $dv;
        }
        return $result;
    }

    private function makeTableBluePrint($props)
    {
        $props = array_values(array_filter($props, fn ($prop) => $prop['control'] == 'relationship_renderer'));
        $result = [];
        // dump($this->superProps);
        foreach ($props as $index => $prop) {
            $table01Name = "table" . str_pad($index + 1, 2, 0, STR_PAD_LEFT);
            $name = $prop['name'];
            $relationships = $this->getSuperProps()['props'][$name]['relationships'];
            if (isset($relationships['table'])) {
                $result[$table01Name] = Str::singular($relationships['table']);
            } else {
                dump("Cannot find table of [$name] in relationship screen of {$this->type}.");
            }
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
            $attachmentItem->hasOrphan = true;
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

    private function loadValueOfBelongsToManyAndAttachments($original, $props)
    {
        $orphanAttachments = $this->loadValueOfOrphanAttachments($props);
        // dump($orphanAttachments);
        if (!method_exists($original, 'getOriginal')) {
            $name = get_class($original);
            abort(500, "The model [$name] does not have getOriginal method.");
        }
        $values = $original->getOriginal();
        foreach ($props as $prop) {
            $name = $prop['column_name'];
            // if (in_array($prop['control'], JsonControls::getDateTimeControls())) {
            //     $values[$name] = DateTimeConcern::convertForLoading($prop['control'], $values[$name]);
            //     continue;
            // }
            switch ($prop['control']) {
                case 'checkbox_2a':
                case 'dropdown_multi_2a':
                    $item = $original->{$name};
                    if ($item) {
                        $values[$name] =  json_encode($item->pluck('id')->toArray());
                    }
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
