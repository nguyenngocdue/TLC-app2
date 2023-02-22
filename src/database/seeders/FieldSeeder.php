<?php

namespace Database\Seeders;

use App\Models\Field;
use Illuminate\Database\Seeder;

class FieldSeeder extends Seeder
{
    public static function getIdFromFieldName($fieldName)
    {
        return Field::where('name', $fieldName)->firstOrFail()->id;
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
            // 'insp_fail_photos' => 13,
            // 'workplaceRel1' => 14,
            // 'workplaceRel2' => 15,
            'comment_attachment' => 15,
            'checkboxYesNo' => 16,
            'checkboxPassFail' => 17,
            'dropdownMultiYesNo' => 18,
            'dropdownMultiPassFail' => 19,
            'checkboxZut1' => 20,
            'dropdownMultiZut1' => 21,
            // 'newCheckbox' => 22,
            'getNoOfYesNo' => 23,
            'getOnHoldOfYesNo' => 24,
            'getFailedOfPassFail' => 25,
            'getOnHoldOfPassFail' => 26,
            'getDefMonitors' => 27,
            'comment_by_clinic' => 28,
            'comment_by_line_manager' => 29,
            'comment_by_general_manager' => 30,
            'dropdownMonitors' => 31,
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
            Field::create(['id' => $id, 'name' => $key, 'slug' => $key]);
        }
    }
}
