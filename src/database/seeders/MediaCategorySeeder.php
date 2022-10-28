<?php

namespace Database\Seeders;

use App\Models\Media_category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MediaCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Media_category::create(['name' => 'attachment_1', 'descriptions' => '', 'slug' => 'attachment_1']);
        Media_category::create(['name' => 'attachment_2', 'descriptions' => '', 'slug' => 'attachment_2']);
        Media_category::create(['name' => 'attachment_3', 'descriptions' => '', 'slug' => 'attachment_3']);
        Media_category::create(['name' => 'attachment_4', 'descriptions' => '', 'slug' => 'attachment_4']);
        Media_category::create(['name' => 'attachment_5', 'descriptions' => '', 'slug' => 'attachment_5']);
    }
}
