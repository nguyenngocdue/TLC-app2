<?php

namespace Database\Seeders;

use App\Models\Comment_category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Comment_category::create(['name' => 'comment_1', 'description' => '', 'slug' => 'comment_1']);
        Comment_category::create(['name' => 'comment_2', 'description' => '', 'slug' => 'comment_2']);
        Comment_category::create(['name' => 'comment_3', 'description' => '', 'slug' => 'comment_3']);
        Comment_category::create(['name' => 'comment_4', 'description' => '', 'slug' => 'comment_4']);
        Comment_category::create(['name' => 'comment_5', 'description' => '', 'slug' => 'comment_5']);
    }
}
