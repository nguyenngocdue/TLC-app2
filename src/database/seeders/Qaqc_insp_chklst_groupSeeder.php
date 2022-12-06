<?php

namespace Database\Seeders;

use App\Models\Qaqc_insp_chklst_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Qaqc_insp_chklst_groupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Qaqc_insp_chklst_group::create(["name" => "Module Structure", "slug" => "1"]);
        Qaqc_insp_chklst_group::create(["name" => "Sub-Structure", "slug" => "2"]);
        Qaqc_insp_chklst_group::create(["name" => "Drain Water & Vent System", "slug" => "3"]);
        Qaqc_insp_chklst_group::create(["name" => "Hot & Cold Water System", "slug" => "4"]);
        Qaqc_insp_chklst_group::create(["name" => "Sprinkler System", "slug" => "5"]);
        Qaqc_insp_chklst_group::create(["name" => "Duct System", "slug" => "6"]);
        Qaqc_insp_chklst_group::create(["name" => "Electrical Works", "slug" => "7"]);
        Qaqc_insp_chklst_group::create(["name" => "Internal Wall Linings", "slug" => "8"]);
        Qaqc_insp_chklst_group::create(["name" => "Internal Painting", "slug" => "9"]);
        Qaqc_insp_chklst_group::create(["name" => "Water Proofing System", "slug" => "10"]);
        Qaqc_insp_chklst_group::create(["name" => "Tiling & Grouting", "slug" => "11"]);
        Qaqc_insp_chklst_group::create(["name" => "Sanitary Wares", "slug" => "12"]);
        Qaqc_insp_chklst_group::create(["name" => "Glazing Works", "slug" => "13"]);
        Qaqc_insp_chklst_group::create(["name" => "Internal Finishes", "slug" => "14"]);
        Qaqc_insp_chklst_group::create(["name" => "Doors", "slug" => "15"]);
        Qaqc_insp_chklst_group::create(["name" => "Sliding Doors", "slug" => "16"]);
        Qaqc_insp_chklst_group::create(["name" => "Windows", "slug" => "17"]);
        Qaqc_insp_chklst_group::create(["name" => "External Wall Linings & Finishes", "slug" => "18"]);
        Qaqc_insp_chklst_group::create(["name" => "Roof Water Proofing", "slug" => "19"]);
        Qaqc_insp_chklst_group::create(["name" => "Passive Fire System", "slug" => "20"]);
        Qaqc_insp_chklst_group::create(["name" => "Final Inspection", "slug" => "21"]);
    }
}
