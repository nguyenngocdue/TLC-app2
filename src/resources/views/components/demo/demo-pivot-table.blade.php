{{-- <x-renderer.heading{{-- <x-renderer.heading level=5>Raw Data</x-renderer.heading>
<x-renderer.table :columns="$columns" :dataSource="$dataSource"/>

<x-renderer.heading level=5>Pivot by hr_timesheet_employee_date</x-renderer.heading>
<x-renderer.report.pivot-table key="hr_timesheet_employee_date" :dataSource="$dataSource"/>

<div class="grid grid-cols-12 gap-2">
<div class="col-span-6">
    <x-renderer.heading level=5>Pivot by hr_timesheet_employee_project</x-renderer.heading>
    <x-renderer.report.pivot-table key="hr_timesheet_employee_project" :dataSource="$dataSource"/>
</div>
<div class="col-span-6">
    <x-renderer.heading level=5>Pivot by hr_timesheet_employee_project_not_table_information</x-renderer.heading>
    <x-renderer.report.pivot-table key="hr_timesheet_employee_project_not_table_information" :dataSource="$dataSource"/>
</div>
</div>

<x-renderer.heading level=5>Pivot by employee_project_change_data_field_title</x-renderer.heading>
<x-renderer.report.pivot-table key="employee_project_change_data_field_title" :dataSource="$dataSource"/>

<x-renderer.heading level=5>Pivot by hr_timesheet_project_date</x-renderer.heading>
<x-renderer.report.pivot-table key="hr_timesheet_project_date" :dataSource="$dataSource"/>

<x-renderer.heading level=5>Pivot by hr_timesheet_team_date</x-renderer.heading>
<x-renderer.report.pivot-table key="hr_timesheet_team_date" :dataSource="$dataSource"/>
 --}}