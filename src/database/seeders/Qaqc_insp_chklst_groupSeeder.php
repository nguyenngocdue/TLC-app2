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
        Qaqc_insp_chklst_group::create(["name" => "TLC Inspection Checklist",  "slug" => "0"]);
        Qaqc_insp_chklst_group::create(["name" => "Module Structure", "parent" => 1, "slug" => "1"]);
        Qaqc_insp_chklst_group::create(["name" => "Sub-Structure", "parent" => 1, "slug" => "2"]);
        Qaqc_insp_chklst_group::create(["name" => "Drain Water & Vent System", "parent" => 1, "slug" => "3"]);
        Qaqc_insp_chklst_group::create(["name" => "Hot & Cold Water System", "parent" => 1, "slug" => "4"]);
        Qaqc_insp_chklst_group::create(["name" => "Sprinkler System", "parent" => 1, "slug" => "5"]);
        Qaqc_insp_chklst_group::create(["name" => "Duct System", "parent" => 1, "slug" => "6"]);
        Qaqc_insp_chklst_group::create(["name" => "Electrical Works", "parent" => 1, "slug" => "7"]);
        Qaqc_insp_chklst_group::create(["name" => "Internal Wall Linings", "parent" => 1, "slug" => "8"]);
        Qaqc_insp_chklst_group::create(["name" => "Internal Painting", "parent" => 1, "slug" => "9"]);
        Qaqc_insp_chklst_group::create(["name" => "Water Proofing System",  "parent" => 1, "slug" => "10"]);
        Qaqc_insp_chklst_group::create(["name" => "Tiling & Grouting",  "parent" => 1, "slug" => "11"]);
        Qaqc_insp_chklst_group::create(["name" => "Sanitary Wares",  "parent" => 1, "slug" => "12"]);
        Qaqc_insp_chklst_group::create(["name" => "Glazing Works",  "parent" => 1, "slug" => "13"]);
        Qaqc_insp_chklst_group::create(["name" => "Internal Finishes",  "parent" => 1, "slug" => "14"]);
        Qaqc_insp_chklst_group::create(["name" => "Doors",  "parent" => 1, "slug" => "15"]);
        Qaqc_insp_chklst_group::create(["name" => "Sliding Doors",  "parent" => 1, "slug" => "16"]);
        Qaqc_insp_chklst_group::create(["name" => "Windows",  "parent" => 1, "slug" => "17"]);
        Qaqc_insp_chklst_group::create(["name" => "External Wall Linings & Finishes",  "parent" => 1, "slug" => "18"]);
        Qaqc_insp_chklst_group::create(["name" => "Roof Water Proofing",  "parent" => 1, "slug" => "19"]);
        Qaqc_insp_chklst_group::create(["name" => "Passive Fire System",  "parent" => 1, "slug" => "20"]);
        Qaqc_insp_chklst_group::create(["name" => "Final Inspection",  "parent" => 1, "slug" => "21"]);
    }
}
