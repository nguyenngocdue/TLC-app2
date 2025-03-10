<?php

namespace Database\Seeders;

use App\Models\Field;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class FieldSeeder extends Seeder
{
    static $fieldDB = null;
    static function getFieldDb()
    {
        if (is_null(static::$fieldDB)) {
            static::$fieldDB = Field::all();
        }
        return static::$fieldDB;
    }

    static $fieldCache = [];
    static function getFieldBy($key0, $value)
    {
        $db = static::getFieldDb();
        $key = "{$key0}_{$value}";
        if (!isset(static::$fieldCache[$key])) {
            foreach ($db as $line) {
                if ($line->{$key0} == $value) {
                    // dump($line);
                    static::$fieldCache[$key] = $line;
                    break;
                }
            }
        }
        $result = static::$fieldCache[$key] ?? null;
        // if (is_null($result)) dd("[" . $value . "] has not been found in table Field.");
        return $result;
    }

    public static function getIdFromFieldName($fieldName)
    {
        try {
            $line = static::getFieldBy('name', $fieldName);
            if (!$line) {
                $msg = "$fieldName is not found in Field Table. Auto generated by FieldSeeder.";
                // Log::info($msg);
                dump($msg);
                $inserted = Field::create([
                    'name' => $fieldName,
                    'owner_id' => 1,
                    'description' => 'Auto generated by FieldSeeder',
                ]);
                return $inserted->id;
            }
            return $line ? $line->id : null;
            // return Field::where('name', $fieldName)->firstOrFail()->id;
        } catch (\Exception $e) {
            abort(404, "Cant find in Field table the name [" . $fieldName . "]");
            // dd($e);
        }
    }
    public static function getNameFromFieldId($fieldId)
    {
        try {
            $line = static::getFieldBy('id', $fieldId);
            if (!$line) {
                $msg = "#$fieldId is not found in Field Table.";
                // Log::info($msg);
                dump($msg);
            }
            return $line ? $line->id : null;
            // return Field::where('id', $fieldId)->firstOrFail()->name;
        } catch (\Exception $e) {
            abort(404, "Cant find in Field table the id [" . $fieldId . "]");
            // dd($e);
        }
    }

    private static function dataSource()
    {
        return [
            'attachment_1' => 1,
            'attachment_2' => 2,
            'attachment_3' => 3,
            'attachment_4' => 4,
            'attachment_5' => 5,
            'comment_1' => 6,
            'comment_2' => 7,
            'comment_3' => 8,
            'comment_4' => 9,
            'comment_5' => 10,
            'featured_image' => 11,
            'insp_photos' => 12,
            'insp_comments' => 13,
            // '' => 13,
            // '' => 14,
            'comment_attachment' => 15,
            'checkboxYesNo' => 16,
            'checkboxPassFail' => 17,
            'dropdownMultiYesNo' => 18,
            'dropdownMultiPassFail' => 19,
            'checkboxZut1' => 20,
            'dropdownMultiZut1' => 21,
            'dropdownMonitorsZut9' => 22,
            // 'getNoOfYesNo' => 23,
            // 'getOnHoldOfYesNo' => 24,
            // 'getFailedOfPassFail' => 25,
            // 'getOnHoldOfPassFail' => 26,
            'comment_rejected_reason' => 27,
            'comment_by_clinic' => 28,
            'comment_by_line_manager' => 29,
            'comment_by_general_manager' => 30,
            'getMonitors1' => 31,
            'getMonitors2' => 32,
            'getMonitors3' => 33,
            'getMonitors4' => 34,
            'getMonitors5' => 35,
            'getMonitors6' => 36,
            'getMonitors7' => 37,
            'getMonitors8' => 38,
            'getMonitors9' => 39,
            'getDefMonitors1' => 41,
            'getDefMonitors2' => 42,
            'getDefMonitors3' => 43,
            'getDefMonitors4' => 44,
            'getDefMonitors5' => 45,
            'getDefMonitors6' => 46,
            'getDefMonitors7' => 47,
            'getDefMonitors8' => 48,
            'getDefMonitors9' => 49,

            'comment_asm_rejected_reason' => 50,
            'comment_insp_rejected_reason' => 51,
            'comment_approver_decision' => 52,
            'comment_inspector_decision' => 53,

            // 'getSignOff' => 61,
            'getDefSignOff' => 71,
        ];
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::dataSource() as $key => $id) {
            Field::create([
                'id' => $id, 'name' => $key, 'owner_id' => 1,
                'slug' => $key
            ]);
        }
    }
}
