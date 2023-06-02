<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TimesheetStaffRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'timesheetable_type' => 'required',
            'timesheetable_id' => 'required',
            'user_id' => 'required',
            'start_time' => 'required',
            'duration_in_min' => 'required',
            'project_id' => 'required',
            'sub_project_id' => 'required',
            'prod_routing_id' => 'required',
            'lod_id' => 'required',
            'owner_id' => 'required',
        ];
    }
}
