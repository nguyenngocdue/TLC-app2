<?php

namespace Database\Seeders;

use App\Models\Attachment_category;
use Illuminate\Database\Seeder;

class AttachmentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Attachment_category::create(['name' => 'attachment_1', 'description' => '', 'slug' => 'attachment_1']);
        Attachment_category::create(['name' => 'attachment_2', 'description' => '', 'slug' => 'attachment_2']);
        Attachment_category::create(['name' => 'attachment_3', 'description' => '', 'slug' => 'attachment_3']);
        Attachment_category::create(['name' => 'attachment_4', 'description' => '', 'slug' => 'attachment_4']);
        Attachment_category::create(['name' => 'attachment_5', 'description' => '', 'slug' => 'attachment_5']);
        Attachment_category::create(['name' => 'featured_image', 'description' => '', 'slug' => 'featured_image']);
        Attachment_category::create(['name' => 'insp_photos', 'description' => '', 'slug' => 'insp_photos']);
        Attachment_category::create(['name' => 'insp_fail_photos', 'description' => '', 'slug' => 'insp_fail_photos']);
    }
}
