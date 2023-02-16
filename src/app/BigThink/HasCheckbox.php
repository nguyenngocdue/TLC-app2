<?php

namespace App\BigThink;

use App\Models\Field;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait HasCheckbox
{
    function guessFieldId($fieldNameOrId)
    {
        if (is_numeric($fieldNameOrId)) return $fieldNameOrId;
        try {
            $fieldId = Field::where('name', $fieldNameOrId)->firstOrFail()->id;
        } catch (Exception $e) {
            dump("Create a record in table Field with name as [$fieldNameOrId]");
            dd($e->getMessage());
        }

        return $fieldId;
    }

    function getCheckedByField($fieldNameOrId, $related = "")
    {
        $fieldId = $this->guessFieldId($fieldNameOrId);
        $dataSource = $this->getChecked();
        $result = $dataSource->filter(fn ($item) => $item['field_id'] === $fieldId)->values();
        // dd($fieldId, $dataSource, $result);
        // dd($fieldNameOrId, $fieldId, $dataSource, $result);
        return $result;
    }

    function getChecked()
    {
        // dd($this->id);
        $result0 = DB::table('many_to_many')->where('doc_type', $this::class)->where('doc_id', $this->id)->get();
        if ($result0->count() == 0) return new Collection([]);

        $ids = $result0->pluck('term_id');
        $modelPaths = $result0->pluck('term_type')->unique();
        foreach ($modelPaths as $modelPath) {
            $model = App::make($modelPath);
            $result1[$modelPath] = $model::whereIn('id', $ids)->get();
            $resultInverted[$modelPath] = Arr::keyBy($result1[$modelPath], 'id');
        }

        $result = [];
        foreach ($result0 as $item) {
            // error_log($item->term_id);
            // dd($item);
            $modelPath = $item->term_type;
            if (isset($resultInverted[$modelPath][$item->term_id])) {
                $objToBeCloned = $resultInverted[$modelPath][$item->term_id];
                $origin = clone $objToBeCloned;
                $origin->field_id = $item->field_id;
                $origin->pivot = [
                    'created_at' => $item->created_at,
                    'json' => json_decode($item->json),
                ];
                $result[] = $origin;
            } else {
                dump("ID #{$item->term_id} not found in $modelPath (HasCheckbox)");
            }
        }
        return new Collection($result);
    }

    private function prepareForAttach($fieldId, $termModelPath, array $ids)
    {
        $line = ['field_id' => $fieldId, 'doc_type' => $this::class, 'doc_id' => $this->id, 'term_type' => $termModelPath,];
        $result = [];
        $idsAssoc = Arr::toAssoc($ids);
        foreach ($idsAssoc as $id => $pivot) $result[] = $line + ["term_id" => $id, "json" => ($pivot === $id) ? "{}" : json_encode($pivot)];
        return $result;
    }

    //Zunit_test_1::find(1)->attachCheck(14, "App\\Models\\Workplace", [1,2,3])
    //Zunit_test_1::find(1)->attachCheck(15, "App\\Models\\Workplace", [4,5=> ["xyz" => 456],6])
    //Zunit_test_1::find(1)->attachCheck(15, "App\\Models\\Workplace", [7=> ["xyz" => 789],8,9])
    function attachCheck($fieldNameOrId, $termModelPath, array $ids)
    {
        $fieldId = $this->guessFieldId($fieldNameOrId);
        $result = $this->prepareForAttach($fieldId, $termModelPath, $ids);
        // var_dump($result);
        $count = 0;
        foreach ($result as $item) $count += DB::table('many_to_many')->insert($item);
        return $count;
    }

    //Zunit_test_1::find(1)->detachCheck(14, "App\\Models\\Workplace", [1,2,3])
    //Zunit_test_1::find(1)->detachCheck(15, "App\\Models\\Workplace", [4,5=> ["xyz" => 456],6])
    //Zunit_test_1::find(1)->detachCheck(15, "App\\Models\\Workplace", [7=> ["xyz" => 789],8,9])
    function detachCheck($fieldNameOrId, $termModelPath, array $ids)
    {
        $fieldId = $this->guessFieldId($fieldNameOrId);
        $idsAssoc = Arr::toAssoc($ids); //[1,2,3]
        $toBeDetached = array_keys($idsAssoc);
        $count = 0;
        // dump($toBeDetached, $idsAssoc);
        foreach ($toBeDetached as $id) {
            $count += DB::table('many_to_many')
                ->where("field_id", $fieldId)
                ->where("doc_type", $this::class)
                ->where("doc_id", $this->id)
                ->where("term_type", $termModelPath)
                ->where("term_id", $id)
                ->delete();
        }
        return $count;
    }

    //Zunit_test_1::find(1)->syncCheck(14, "App\\Models\\Workplace", [1,2,3=>["def"=>345]])
    //Zunit_test_1::find(1)->syncCheck(14, "App\\Models\\Workplace", [1,2,4=>["def"=>465]])
    //Zunit_test_1::find(1)->syncCheck(14, "App\\Models\\Workplace", [1,2,4=>["def"=>789]])

    //Zunit_test_1::find(1)->syncCheck(14, "App\\Models\\Workplace", [2,3])
    //Zunit_test_1::find(1)->syncCheck(14, "App\\Models\\Workplace", [2,3,5])
    //Zunit_test_1::find(1)->syncCheck(14, "App\\Models\\Workplace", [])
    function syncCheck($fieldNameOrId, $termModelPath, array $ids)
    {
        $fieldId = $this->guessFieldId($fieldNameOrId);
        $result = $this->prepareForAttach($fieldId, $termModelPath, $ids);
        $toBeSynced = array_map(fn ($item) => $item["term_id"], $result);

        //This section is to determine to be Added and Deleted list
        $current = DB::table('many_to_many')
            ->where("field_id", $fieldId)
            ->where("doc_type", $this::class)
            ->where("doc_id", $this->id)
            ->where("term_type", $termModelPath)
            ->get();
        $currentIds = $current->map(fn ($item) => $item->term_id)->toArray();
        $toBeAddedList = array_values(array_diff($toBeSynced, $currentIds));
        $toBeDeletedList = array_values(array_diff($currentIds, $toBeSynced));

        //This section is to handle sync for ids have Assoc.
        $toBeKeptList = array_diff(array_diff($currentIds, $toBeDeletedList), $toBeAddedList);
        array_push($toBeAddedList, ...$toBeKeptList);
        array_push($toBeDeletedList, ...$toBeKeptList);
        // dd($toBeAddedList);

        //This section is to enrich the to be added list
        $arrAssoc = Arr::toAssoc($ids);
        $toBeAddedListEnriched = [];
        foreach ($toBeAddedList as $id) $toBeAddedListEnriched[$id] = $arrAssoc[$id];

        $count = 0;
        // dd('tobeDel', $arrAssoc, $toBeAddedList, $toBeAddedListEnriched);
        $count += $this->detachCheck($fieldId, $termModelPath, $toBeDeletedList);
        // dd($arrAssoc, "DEl", $toBeDeletedList, $toBeAddedListEnriched);
        //Detach must be before attach to make sure the Kept array is removed
        $count += $this->attachCheck($fieldId, $termModelPath, $toBeAddedListEnriched);
        return $count - 2 * sizeof($toBeKeptList);
    }
}
