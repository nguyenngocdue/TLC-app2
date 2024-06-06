<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class WelcomeFortuneController extends Controller
{
    function getType()
    {
        return "dashboard";
    }
    public function index(Request $request)
    {
        // $connection = env('SQLSERVER_CONNECTION', 'sqlsrv');
        // // dump($connection);

        // $tables = DB::connection($connection)->select('SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = \'BASE TABLE\' AND TABLE_CATALOG= ?', [env('SQLSERVER_DATABASE')]);
        // dump($tables);

        $map = [
            20 => 'checkbox', //checkboxZut1
            21 => 'dropdown_multi',  //dropdownMultiZut1
            31 => 'monitor_1', //getMonitors1
            32 => 'monitor_2',
            33 => 'monitor_3',
            34 => 'monitor_4',
            35 => 'monitor_5',
            36 => 'monitor_6',
            37 => 'monitor_7',
            38 => 'monitor_8',
            39 => 'monitor_9',
            41 => 'def_monitor_1', //getDefMonitors1
            42 => 'def_monitor_2',
            43 => 'def_monitor_3',
            44 => 'def_monitor_4',
            45 => 'def_monitor_5',
            46 => 'def_monitor_6',
            47 => 'def_monitor_7',
            48 => 'def_monitor_8',
            49 => 'def_monitor_9',
            101 => 'main_affected_part', //mainAffectedPart
            102 => 'nature_of_injury',  //natureOfInjury
            103 => 'treatment_instruction', //treatmentInstruction
            104 => 'cause_of_issue', //causeOfIssue
            105 => 'activity_lead_to_issue', //activityLeadToIssue
            106 => 'immediate_cause', //immediateCause
            107 => 'issue_root_cause', //issueRootCause

            137 => '', //getProdRoutingsOfWirDescription, getWirDescriptions
            138 => "ot_member", //getOtMembers, getOtTeams
            139 => "", //getLodsOfTask
            147 => 'project_member', //getProjectMembers
            151 => '', //getProdRoutingsOfInspTmpl, getChklstTmpls
            152 => '', //getDisciplinesOfTask, getTasksOfDiscipline

            153 => '', //getParentTasks, getChildrenSubTasks
            155 => '', //getProdRoutingsOfSubProject, getSubProjects
            156 => 'tsht_member', //getTshtMembers , getTshtTeams

            161 => 'type_of_change', //getTypesOfChangeOfEco
            164 => '', //getSubProjectsOfEco
            175 => '', //getSubProjectsOfExpenseClaim
            186 => 'site_member', //getSiteMembers
            195 => 'show_me_on', //getScreensShowMeOn

            // 202
            203 => 'ext_insp', //getExternalInspectorsOfSubProject, getSubProjectsOfExternalInspector
            205 => 'ext_insp', //getExternalInspectorsOfQaqcInspTmpl, getQaqcInspTmplsOfExternalInspector
            206 => 'ext_insp', //getExternalInspectorsOfProdRouting, getProdRoutingsOfExternalInspector

            213 => '', //getSkillsOfDepartment
            219 => '3rd_party', //signature_qaqc_chklst_3rd_party_list

            220 => 'peer_list', //signature_eco_peers_list

            228 => 'qaqc_list', //signature_qaqc_punchlist_qaqc_list
            230 => 'production_list', //signature_qaqc_punchlist_production_list

            // 249
            250 => 'council_member', //getSubProjectsOfCouncilMember, getCouncilMembersOfSubProject
            251 => 'council_member', //getQaqcInspTmplsOfCouncilMember, getCouncilMembersOfQaqcInspTmpl
            252 => 'council_member', //getProdRoutingsOfCouncilMember, getCouncilMembersOfProdRouting 

        ];

        // $reversed =  []; ///*139,152,153,*/156, 161, 164, 186, 195, 203, 205, 206, 219, 220, 228, 230];
        $lines = DB::table("many_to_many")
            ->where('field_id', 31)
            // ->where('field_id', '>', 31)
            ->whereNotIn('field_id', [138, 147, 175, 202, 249])
            ->get();
        // -> reversed

        $count = 0;
        foreach ($lines as $line) {
            $count++;
            $field_id = $line->field_id;
            if (!isset($map[$field_id])) dd("Unknown field_id: $field_id");
            Log::info($field_id);
            if ($count % 100 == 0) Log::info($field_id . " " . $count);
            $relation = $map[$field_id] ? "_" . $map[$field_id] : "";

            $doc_type = $line->doc_type;
            $term_type = $line->term_type;

            $create_at = $line->created_at;

            $tableName1plural = (new $doc_type)->getTable();
            $tableName2plural = (new $term_type)->getTable();

            $tableName1singularOri = Str::singular($tableName1plural);
            $tableName2singularOri = Str::singular($tableName2plural);

            $doc_id = $line->doc_id;
            $term_id = $line->term_id;

            if ($tableName1plural > $tableName2plural) {
                $temp = $tableName1plural;
                $tableName1plural = $tableName2plural;
                $tableName2plural = $temp;
            }

            $tableName1singular = Str::singular($tableName1plural);
            $tableName2singular = Str::singular($tableName2plural);

            $pivotName = "ym2m_{$tableName1singular}_{$tableName2singular}" . $relation;
            DB::table($pivotName)->insert([
                $tableName1singularOri . "_id" => $doc_id,
                $tableName2singularOri . "_id" => $term_id,
                'owner_id' => 1,
                "created_at" => $create_at,
                "updated_at" => $create_at,
            ]);
            // break;
        }

        dump("Migrated $count lines.");
        return view("welcome-fortune", []);
    }
}
