<?php

namespace Database\Seeders;

use App\Models\Attachment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttachmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Attachment::factory()
            ->count(50)
            ->create();
    }
}
