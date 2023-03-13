<?php

namespace App\View\Components\Modals;

use App\Utils\ClassList;
use Database\Seeders\FieldSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class ParentId7UserOt extends Component
{
    // use TraitMorphTo;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name,
        private $selected = "",
        private $multiple = false,
        // private $type,
        private $readOnly = false,
        private $control = 'dropdown2', // or 'radio-or-checkbox2'
    ) {
        if (old($name)) $this->selected = 1 * old($name);
        // dump($this->selected);
    }

    private function getDataSource($attr_name)
    {
        $fieldId = FieldSeeder::getIdFromFieldName('getOtMembers');
        // dump($fieldId);
        $sql = "SELECT 
                    u.id AS id, 
                    u.name AS name, 
                    u.employeeid AS description, 
                    ut.id AS $attr_name,
                    vua.url_thumbnail AS avatar,
                    vrmn.remaining_hours AS subtitle
                FROM 
                    user_team_ots ut, 
                    view_user_avatar vua,
                    many_to_many m2m, 
                    users u
                    LEFT JOIN view_otr_remainings vrmn ON (
                        vrmn.user_id=u.id
                        AND vrmn.year_month0='2023-02'
                        )
                WHERE 1=1
                    AND m2m.field_id=$fieldId
                    AND m2m.doc_type='App\\\\Models\\\\User_team_ot'
                    AND ut.id=m2m.doc_id
                    AND m2m.term_type='App\\\\Models\\\\User'
                    AND u.id=m2m.term_id
                    AND u.resigned != 1
                    AND u.id=vua.u_id
                    
                ORDER BY u.name
                ";
        $result = DB::select($sql);

        foreach ($result as &$row) {
            if ($row->avatar) {
                $row->avatar  = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/' . $row->avatar;
            } else {
                $row->avatar = "/images/avatar.jpg";
            }
            if (!$row->subtitle) {
                $row->subtitle = 40;
            }
            $row->subtitle = "Remaining OT: " . $row->subtitle . "h";
            // $row->name = "<b>" . $row->name . "</b>";
        }
        // dump($result);

        return $result;
    }


    private function renderJS($tableName, $objectTypeStr, $objectIdStr)
    {
        $attr_name = $tableName . '_parent_fake_id';
        $k = [$tableName => $this->getDataSource($attr_name),];
        $listenersOfDropdown2 = [
            [
                'listen_action' => 'reduce',
                'column_name' => $objectIdStr,
                'listen_to_attrs' => [$attr_name],
                'listen_to_fields' => [$objectIdStr],
                'listen_to_tables' => [$tableName],
                'table_name' => $tableName,
                // 'attrs_to_compare' => ['id'],
                'triggers' => [$objectTypeStr],
            ],
        ];
        $str = "";
        $str .= "<script>";
        $str .= " k = {...k, ..." . json_encode($k) . "};";
        $str .= " listenersOfDropdown2 = [...listenersOfDropdown2, ..." . json_encode($listenersOfDropdown2) . "];";
        $str .= "</script>";
        $str .= "\n";
        echo $str;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $tableName = "modal_" . $this->name;
        $classList = ClassList::DROPDOWN;
        if ($this->control == 'radio-or-checkbox2') {
            $classList = ClassList::RADIO_CHECKBOX;
        }
        $params = [
            'name' => $this->name,
            'id' => $this->name,
            'selected' => json_encode([is_numeric($this->selected) ? $this->selected * 1 : $this->selected]),
            'multipleStr' => $this->multiple ? "multiple" : "",
            'table' => $tableName,
            'readOnly' => $this->readOnly,
            'classList' => $classList,
            // 'entity' => $this->type,
            'multiple' => $this->multiple ? true : false,
            'span' => 3,
        ];
        $this->renderJS($tableName, 'ot_team', $this->name);
        // dump($params);
        return view('components.controls.has-data-source.' . $this->control, $params);
    }
}
