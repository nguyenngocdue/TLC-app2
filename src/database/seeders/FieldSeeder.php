<?php

namespace Database\Seeders;

use App\Models\Field;
use Illuminate\Database\Seeder;

class FieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Field::create(['name' => 'attachment_1', 'slug' => 'attachment_1']);
        Field::create(['name' => 'attachment_2', 'slug' => 'attachment_2']);
        Field::create(['name' => 'attachment_3', 'slug' => 'attachment_3']);
        Field::create(['name' => 'attachment_4', 'slug' => 'attachment_4']);
        Field::create(['name' => 'attachment_5', 'slug' => 'attachment_5']);
        Field::create(['name' => 'comment_1', 'slug' => 'comment_1']);
        Field::create(['name' => 'comment_2', 'slug' => 'comment_2']);
        Field::create(['name' => 'comment_3', 'slug' => 'comment_3']);
        Field::create(['name' => 'comment_4', 'slug' => 'comment_4']);
        Field::create(['name' => 'comment_5', 'slug' => 'comment_5']);
        Field::create(['name' => 'featured_image', 'slug' => 'featured_image']);
        Field::create(['name' => 'insp_photos', 'slug' => 'insp_photos']);
        Field::create(['name' => 'insp_fail_photos', 'slug' => 'insp_fail_photos']);
        Field::create(['name' => 'workplaceRel1', 'slug' => 'workplaceRel1']);
        Field::create(['name' => 'workplaceRel2', 'slug' => 'workplaceRel2']);
        Field::create(['name' => 'checkboxYesNo', 'slug' => 'checkboxYesNo']);
        Field::create(['name' => 'checkboxPassFail', 'slug' => 'checkboxPassFail']);
        Field::create(['name' => 'dropdownMultiYesNo', 'slug' => 'dropdownMultiYesNo']);
        Field::create(['name' => 'dropdownMultiPassFail', 'slug' => 'dropdownMultiPassFail']);
        Field::create(['name' => 'checkbox', 'slug' => 'checkbox']);
        Field::create(['name' => 'dropdownMulti', 'slug' => 'dropdownMulti']);
    }
}
