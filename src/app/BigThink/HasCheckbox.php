<?php

namespace App\BigThink;

use App\Utils\Support\CurrentUser;
use Database\Seeders\FieldSeeder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait HasCheckbox
{
    function guessFieldId($fieldNameOrId)
    {
        if (is_numeric($fieldNameOrId)) return [$fieldNameOrId, false];
        // dump(static::getFieldDb());
        // dump(static::getFieldBy("name", 'getMonitors1'));
        // $fieldId = Field::where('name', $fieldNameOrId)->first();
        // $fieldIdReversed = Field::where('reversed_name', $fieldNameOrId)->first();
        $fieldId = FieldSeeder::getFieldBy('name', $fieldNameOrId);
        $fieldIdReversed = FieldSeeder::getFieldBy('reversed_name', $fieldNameOrId);

        // dump($fieldId, $fieldIdReversed);
        if ($fieldId) return [$fieldId->id, false];
        if ($fieldIdReversed) return [$fieldIdReversed->id, true];
        //if not found either name or reversed_name
        // dump("Create a record in table Field with name/reversed_name as [$fieldNameOrId]");
        // dd();
    }

    function getCheckedByField($fieldNameOrId, $relation = null)
    {
        [$fieldId, $reversedDirection] = $this->guessFieldId($fieldNameOrId);
        $dataSource = $this->getChecked($reversedDirection);
        $result = $dataSource->filter(fn ($item) => $item['field_id'] === $fieldId)->values();
        // dd($fieldId, $dataSource, $result);
        // dd($fieldNameOrId, $fieldId, $dataSource, $result);
        return $result;
    }

    private function getFieldSet($reversedDirection)
    {
        return !$reversedDirection ? ['doc_type', 'doc_id', 'term_type', 'term_id'] : ['term_type', 'term_id', 'doc_type', 'doc_id'];
    }

    private static $singleton01 = [];
    private static $warnings01 = [];
    private static function getWhereIn($model, $modelPath, $column_name, $ids0)
    {
        $key = $modelPath . "_" . $column_name;

        //Cache database table to singleton01
        if (!isset(static::$singleton01[$key])) {
            $result0 = $model::get();
            foreach ($result0 as $line) {
                static::$singleton01[$key][$line->id] = $line;
            }
        }

        // $result = [];
        // $subKey = $ids0->join(",");
        // if (!isset(static::$singleton01[$key][$subKey])) {
        //     foreach ($ids0 as $id) {
        //         if (!isset(static::$singleton01[$key][$id])) {
        //             if (!isset(static::$warnings01["{$id}_{$key}"])) {
        //                 static::$warnings01["{$id}_{$key}"] = true;
        //                 dump("Cannot find #$id for $key when run getWhereIn");
        //             }
        //         } else {
        //             $result[] = static::$singleton01[$key][$id];
        //         }
        //     }
        //     $result = collect($result);
        //     static::$singleton01[$key][$subKey] = $result;
        // }
        // $result = static::$singleton01[$key][$subKey];


        $result = [];
        foreach ($ids0 as $id) {
            if (!isset(static::$singleton01[$key][$id])) {
                if (!isset(static::$warnings01["{$id}_{$key}"])) {
                    static::$warnings01["{$id}_{$key}"] = true;
                    if (CurrentUser::isAdmin()) dump("ADMIN: Cannot find [#$id] for [$modelPath].[$column_name] when run getWhereIn.");
                }
            } else {
                $result[] = static::$singleton01[$key][$id];
            }
        }
        $result = collect($result);


        // dump($result);
        // $key = $modelPath . "_" . $column_name . "_" . $ids0->join(",");
        // if (!isset(static::$singleton01[$key])) {
        //     static::$singleton01[$key] = $model::whereIn('id', $ids0)->get();
        // }
        // $result = static::$singleton01[$key];
        return $result;
    }

    private static $singleton12 = [];
    private static function executeQuery($leftType, $thisClass)
    {
        $key = "{$leftType}_{$thisClass}";
        if (!isset(static::$singleton12[$key])) {
            $sql = DB::table('many_to_many')->where($leftType, $thisClass);
            static::$singleton12[$key] = $sql->get();
        }
        return static::$singleton12[$key];
    }

    private static $singleton02 = [];
    private static $count = 0;
    private static function getManyToMany($leftType, $thisClass, $leftId, $thisId)
    {
        $key = $leftType . "_" . $thisClass;
        if (!isset(static::$singleton02[$key])) {
            $result0 = static::executeQuery($leftType, $thisClass);
            // Log::info($sql->toSql());
            foreach ($result0 as $line) {
                $subKey_i = $leftId . "_" . $line->{$leftId};
                static::$singleton02[$key][$subKey_i][] = $line;
            }
            foreach ($result0 as $line) {
                $subKey_i = $leftId . "_" . $line->{$leftId};
                static::$singleton02[$key][$subKey_i] = collect(static::$singleton02[$key][$subKey_i]);
            }
        }
        $subKey = $leftId . "_" . $thisId;
        $result = static::$singleton02[$key][$subKey] ?? collect([]);
        return $result;

        // $key = $leftType . "_" . $thisClass . "_" . $leftId . "_" . $thisId;
        // if (!isset(static::$singleton02[$key])) {
        //     static::$singleton02[$key] = DB::table('many_to_many')->where($leftType, $thisClass)->where($leftId, $thisId)->get();
        // }
        // return static::$singleton02[$key];
    }

    private static $singleton03 = [];
    private function groupIdsByModelExpensive($dataSource, $rightType, $rightId, $leftKey = null)
    {
        $result = [];
        foreach ($dataSource as $value) $result[$value->{$rightType}][] = $value->{$rightId};
        foreach ($result as &$line) $line = (collect($line))->sort();
        return $result;
    }
    function groupIdsByModel($dataSource,  $rightType, $rightId, $leftKey)
    {
        $result = [];
        if (!isset(static::$singleton03[$leftKey])) {
            $result = $this->groupIdsByModelExpensive($dataSource, $rightType, $rightId);
            static::$singleton03[$leftKey] = $result;
        }
        $result = static::$singleton03[$leftKey];
        return $result;
    }

    function getChecked($reversedDirection = false)
    {
        // dd($this->id);
        [$leftType, $leftId, $rightType, $rightId] = $this->getFieldSet($reversedDirection);
        // dump($leftType . ":" . $this::class . " " . $leftId . ":" . $this->id . " " . $rightType . " " . $rightId . " " . $reversedDirection);

        $result0 = static::getManyToMany($leftType, $this::class, $leftId, $this->id);
        // $result0 = DB::table('many_to_many')->where($leftType, $this::class)->where($leftId, $this->id)->get();
        // dump($result0);
        if ($result0->count() == 0) return collect([]);
        $leftKey = $leftType . "-" . $this::class . "-" . $leftId . "-" . $this->id;
        $modelPathArray = $this->groupIdsByModel($result0,  $rightType, $rightId, $leftKey);
        // $ids0 = $result0->pluck($rightId)->sort();
        // $modelPaths0 = $result0->pluck($rightType)->unique();
        // dump($modelPaths0->join(",") . " --> " . $ids0->join(","));
        foreach ($modelPathArray as $modelPath => $ids0) {
            // foreach ($modelPaths0 as $modelPath) {
            if (class_exists($modelPath)) {
                $model = App::make($modelPath);
                $tmp[$modelPath] = static::getWhereIn($model, $modelPath, 'id', $ids0);
                // $tmp[$modelPath] = $model::whereIn('id', $ids0)->get();
                $resultInverted0[$modelPath] = Arr::keyBy($tmp[$modelPath], 'id');
            } else {
                if (CurrentUser::isAdmin()) dump("ADMIN: Class [$modelPath] does not exist, please double check [$rightType] in [many_to_many] table.");
            }
        }

        $result = [];
        foreach ($result0 as $item) {
            $modelPath = $item->{$rightType};
            if (isset($resultInverted0[$modelPath][$item->{$rightId}])) {
                $objToBeCloned = $resultInverted0[$modelPath][$item->{$rightId}];
                $origin = clone $objToBeCloned;
                $origin->field_id = $item->field_id;
                $origin->pivot = [
                    'created_at' => $item->created_at,
                    'json' => json_decode($item->json),
                ];
                $result[] = $origin;
            } else {
                if (CurrentUser::isAdmin()) dump("ADMIN: ID #{$item->{$rightId}} not found in [$modelPath] (HasCheckbox)");
            }
        }

        $result = collect($result);
        return $result;
    }

    private function prepareForAttach($fieldId, $termModelPath, array $ids, $reversedDirection)
    {
        [$leftType, $leftId, $rightType, $rightId] = $this->getFieldSet($reversedDirection);
        $line = ['field_id' => $fieldId, $leftType => $this::class, $leftId => $this->id, $rightType => $termModelPath,];
        $result = [];
        $idsAssoc = Arr::toAssoc($ids);
        foreach ($idsAssoc as $id => $pivot) $result[] = $line + [$rightId => $id, "json" => ($pivot === $id) ? "{}" : json_encode($pivot)];
        return $result;
    }

    //Zunit_test_01::find(1)->attachCheck(20, "App\\Models\\Workplace", [1,2,3])
    //Zunit_test_01::find(1)->attachCheck(20, "App\\Models\\Workplace", [4,5=> ["xyz" => 456],6])
    //Zunit_test_01::find(1)->attachCheck(20, "App\\Models\\Workplace", [7=> ["xyz" => 789],8,9])
    function attachCheck($fieldNameOrId, $termModelPath, $ids)
    {
        if (is_string($ids)) $ids = explode(",", $ids);
        [$fieldId, $reversedDirection] = $this->guessFieldId($fieldNameOrId);
        $result = $this->prepareForAttach($fieldId, $termModelPath, $ids, $reversedDirection);
        // var_dump($result);
        $count = 0;
        foreach ($result as $item) $count += DB::table('many_to_many')->insert($item);
        return $count;
    }

    //Zunit_test_01::find(1)->detachCheck(20, "App\\Models\\Workplace", [1,2,3])
    //Zunit_test_01::find(1)->detachCheck(20, "App\\Models\\Workplace", [4,5=> ["xyz" => 456],6])
    //Zunit_test_01::find(1)->detachCheck(20, "App\\Models\\Workplace", [7=> ["xyz" => 789],8,9])
    function detachCheck($fieldNameOrId, $termModelPath, array $ids)
    {
        [$fieldId, $reversedDirection] = $this->guessFieldId($fieldNameOrId);
        [$leftType, $leftId, $rightType, $rightId] = $this->getFieldSet($reversedDirection);
        $idsAssoc = Arr::toAssoc($ids); //[1,2,3]
        $toBeDetached = array_keys($idsAssoc);
        $count = 0;
        // dump($toBeDetached, $idsAssoc);
        foreach ($toBeDetached as $id) {
            $count += DB::table('many_to_many')
                ->where("field_id", $fieldId)
                ->where($leftType, $this::class)
                ->where($leftId, $this->id)
                ->where($rightType, $termModelPath)
                ->where($rightId, $id)
                ->delete();
        }
        return $count;
    }

    //Zunit_test_01::find(1)->syncCheck(20, "App\\Models\\Workplace", [1,2,3=>["def"=>345]])
    //Zunit_test_01::find(1)->syncCheck(20, "App\\Models\\Workplace", [1,2,4=>["def"=>465]])
    //Zunit_test_01::find(1)->syncCheck(20, "App\\Models\\Workplace", [1,2,4=>["def"=>789]])

    //Zunit_test_01::find(1)->syncCheck(20, "App\\Models\\Workplace", [2,3])
    //Zunit_test_01::find(1)->syncCheck(20, "App\\Models\\Workplace", [2,3,5])
    //Zunit_test_01::find(1)->syncCheck(20, "App\\Models\\Workplace", [])
    function syncCheck($fieldNameOrId, $termModelPath, array $ids)
    {
        [$fieldId, $reversedDirection] = $this->guessFieldId($fieldNameOrId);
        $result = $this->prepareForAttach($fieldId, $termModelPath, $ids, $reversedDirection);
        [$leftType, $leftId, $rightType, $rightId] = $this->getFieldSet($reversedDirection);
        $toBeSynced = array_map(fn ($item) => $item[$rightId], $result);

        //This section is to determine to be Added and Deleted list
        $current = DB::table('many_to_many')
            ->where("field_id", $fieldId)
            ->where($leftType, $this::class)
            ->where($leftId, $this->id)
            ->where($rightType, $termModelPath)
            ->get();
        $currentIds = $current->map(fn ($item) => $item->{$rightId})->toArray();
        $toBeAddedList = array_values(array_diff($toBeSynced, $currentIds));
        $toBeDeletedList = array_values(array_diff($currentIds, $toBeSynced));
        if ($fieldId == 155) {
            Log::channel("emergency")->info("syncCheck getProdRoutingsOfSubProject ids: [" . join(", ", $ids) . "]");
            Log::channel("emergency")->info("syncCheck getProdRoutingsOfSubProject toBeAdded: [" . join(", ", $toBeAddedList) . "]");
            Log::channel("emergency")->info("syncCheck getProdRoutingsOfSubProject toBeDeleted: [" . join(", ", $toBeDeletedList) . "]");
        }

        //This section is to handle sync for ids have Assoc.
        $toBeKeptList = array_diff(array_diff($currentIds, $toBeDeletedList), $toBeAddedList);
        array_push($toBeAddedList, ...$toBeKeptList);
        array_push($toBeDeletedList, ...$toBeKeptList);
        // dump($toBeAddedList);
        // dump($toBeDeletedList);

        //This section is to enrich the to be added list
        $arrAssoc = Arr::toAssoc($ids);
        $toBeAddedListEnriched = [];
        foreach ($toBeAddedList as $id) $toBeAddedListEnriched[$id] = $arrAssoc[$id];

        $count = 0;
        // dd('tobeDel', $arrAssoc, $toBeAddedList, $toBeAddedListEnriched);
        $count += $this->detachCheck($fieldNameOrId, $termModelPath, $toBeDeletedList);
        // dd($arrAssoc, "DEl", $toBeDeletedList, $toBeAddedListEnriched);
        //Detach must be before attach to make sure the Kept array is removed
        $count += $this->attachCheck($fieldNameOrId, $termModelPath, $toBeAddedListEnriched);
        return $count - 2 * sizeof($toBeKeptList);
    }
}
