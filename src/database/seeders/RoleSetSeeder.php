<?php

namespace Database\Seeders;

use App\Models\RoleSet;
use App\Utils\Support\Entities;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoleSetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roleSets = [
            "acct_manager",
            "acct_manager_nz",
            "acct_member",
            "acct_member_nz",
            "bd_manager",
            "bd_member",
            "cpl_manager_nz",
            "cpl_member",
            "dc_ho",
            "dc_manager",
            "dc_ws",
            "des_manager",
            "des_member",
            "dir",
            "dir_nz",
            "fac_admin",
            "fin_member_nz",
            "guest",
            "hr_manager",
            "hr_member",
            "hr_member_nz",
            "hse_manager",
            "hse_member",
            "it_manager",
            "it_member",
            "mgr_asst_nz",
            "newcomer",
            "pln_admin",
            "prod_manager",
            "prod_member",
            "prod_manager_nz",
            "prod_member_nz",
            "proc_manager",
            "proc_member",
            "proc_member_nz",
            "proj_manager",
            "proj_manager_nz",
            "proj_member",
            "proj_member_nz",
            "qaqc_manager",
            "qaqc_member",
            "qs_manager",
            "qs_member",
            "qs_member_nz",
            "supreme_manage",
            "whs_manager",
            "whs_member",
            "worker",
        ];
        foreach ($roleSets as $roleSet) RoleSet::create(['name' => $roleSet]);

        try {
            $entities = Entities::getAll();
            $names = [];
            foreach ($entities as $entity) {
                $name = $entity->getTable();
                $names[] = Str::upper($name);
            }
            $roles = array_map(fn ($item) => "ADMIN-DATA-$item", $names);
            RoleSet::updateOrCreate(['name' => 'admin'])->giveRoleTo([$roles]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
