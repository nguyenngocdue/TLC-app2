<?php

namespace App\View\Components\Modals;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitListenerControl;
use App\Utils\ColorList;
use App\Utils\Constant;
use Database\Seeders\FieldSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;
use Illuminate\Support\Arr;

class ParentId7UserOt extends Component
{
    use TraitListenerControl;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name,
        private $tableName,
        private $selected = "",
        private $multiple = false,
        private $readOnly = false,
        private $control = 'dropdown2', // or 'radio-or-checkbox2'
        private $allowClear = false,
        private $typeToLoadListener = 'any_thing_but_not_null',
    ) {
        $this->selected = Arr::normalizeSelected($this->selected, old($name));
    }

    //modal_ot_user2_parent_fake_id
    private function getDataSource()
    {
        $fieldId = FieldSeeder::getIdFromFieldName('getOtMembers');
        // dump($fieldId);
        // $year_month0 = '2023-02';
        $year_month0 = date(Constant::FORMAT_YEAR_MONTH0);
        $year0 = date(Constant::FORMAT_YEAR);
        $sql = "SELECT 
                    u.id AS id, 
                    u.name0 AS name, 
                    u.employeeid AS description, 
                    ut.id AS 'ot_team_id',
                    vua.url_thumbnail AS avatar,
                    vmrmn.month_remaining_hours AS month_remaining_hours,
                    vyrmn.year_remaining_hours AS year_remaining_hours
                FROM 
                    user_team_ots ut, 
                    view_user_avatar vua,
                    many_to_many m2m, 
                    users u
                    LEFT JOIN view_otr_month_remainings vmrmn ON (
                        vmrmn.user_id=u.id
                        AND vmrmn.year_month0='$year_month0'
                        )
                    LEFT JOIN view_otr_year_remainings vyrmn ON (
                        vyrmn.user_id=u.id
                        AND vyrmn.year0='$year0'
                        )
                WHERE 1=1
                    AND m2m.field_id=$fieldId
                    AND m2m.doc_type='App\\\\Models\\\\User_team_ot'
                    AND ut.id=m2m.doc_id
                    AND m2m.term_type='App\\\\Models\\\\User'
                    AND u.id=m2m.term_id
                    AND u.resigned != 1
                    AND u.id=vua.u_id
                    
                ORDER BY u.name0
                ";
        // Log::info($sql);
        $result = DB::select($sql);

        $standard_month_hours = config("hr.standard_ot_hour_per_month");
        $standard_year_hours = config("hr.standard_ot_hour_per_year");

        foreach ($result as &$row) {
            if ($row->avatar) {
                $row->avatar  = app()->pathMinio() . '/' . $row->avatar;
            } else {
                $row->avatar = "/images/avatar.jpg";
            }
            if (!$row->month_remaining_hours) {
                $row->month_remaining_hours = $standard_month_hours;
            }
            if (!$row->year_remaining_hours) {
                $row->year_remaining_hours = $standard_year_hours;
            }
            // $row->disabled = ($row->month_remaining_hours <= 0) || ($row->year_remaining_hours <= 0);

            $monthColor = ColorList::getBgColorForRemainingOTHours($row->month_remaining_hours, $standard_month_hours);
            $yearColor = ColorList::getBgColorForRemainingOTHours($row->year_remaining_hours, $standard_year_hours);
            $row->subtitle = "";
            $row->subtitle .= "Remaining: ";
            $row->subtitle .=  "<span class='$monthColor mx-1 p-0.5 rounded' title='Remaining OT hours of month $year_month0'>" . $row->month_remaining_hours . "h</span>";
            $row->subtitle .=  "<span class='$yearColor mx-1 p-0.5 rounded' title='Remaining OT hours of year $year0'>" . $row->year_remaining_hours . "h</span>";
        }
        // dump($result);

        return $result;
    }

    private function getListenersOfDropdown2()
    {
        return [
            [
                'listen_action' => 'reduce',
                'column_name' => $this->name,
                'listen_to_attrs' => ['ot_team_id'],
                'listen_to_fields' => [$this->name],
                'listen_to_tables' => [$this->tableName],
                'table_name' => $this->tableName,
                // 'attrs_to_compare' => ['id'],
                'triggers' => ['ot_team_id'],
            ],
        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $params = $this->getParamsForHasDataSource();
        $params['span'] = 3;
        $this->renderJSForK();
        // dump($params);
        return view('components.controls.has-data-source.' . $this->control, $params);
    }
}
