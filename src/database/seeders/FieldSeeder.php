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
        Field::create(['name' => 'attachment_1', 'description' => '', 'slug' => 'attachment_1']);
        Field::create(['name' => 'attachment_2', 'description' => '', 'slug' => 'attachment_2']);
        Field::create(['name' => 'attachment_3', 'description' => '', 'slug' => 'attachment_3']);
        Field::create(['name' => 'attachment_4', 'description' => '', 'slug' => 'attachment_4']);
        Field::create(['name' => 'attachment_5', 'description' => '', 'slug' => 'attachment_5']);
        Field::create(['name' => 'comment_1', 'description' => '', 'slug' => 'comment_1']);
        Field::create(['name' => 'comment_2', 'description' => '', 'slug' => 'comment_2']);
        Field::create(['name' => 'comment_3', 'description' => '', 'slug' => 'comment_3']);
        Field::create(['name' => 'comment_4', 'description' => '', 'slug' => 'comment_4']);
        Field::create(['name' => 'comment_5', 'description' => '', 'slug' => 'comment_5']);
        Field::create(['name' => 'featured_image', 'description' => '', 'slug' => 'featured_image']);
        Field::create(['name' => 'insp_photos', 'description' => '', 'slug' => 'insp_photos']);
        Field::create(['name' => 'insp_fail_photos', 'description' => '', 'slug' => 'insp_fail_photos']);
    }
}
