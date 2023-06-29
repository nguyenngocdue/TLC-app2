<x-renderer.heading level=5>Raw Data</x-renderer.heading>
<x-renderer.table :columns="$columns" :dataSource="$dataSource"/>

{{-- <x-renderer.heading level=5>Pivot by apple_store_product_per_date</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_product_per_date" :dataSource="$dataSource"/> --}}

<x-renderer.heading level=5>Pivot by apple_store_product_per_date</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_product_per_date" :dataSource="$dataSource"/>

<x-renderer.heading level=5>Pivot by apple_store_category_per_date</x-renderer.heading>
<x-renderer.report.pivot-table key="apple_store_category_per_date" :dataSource="$dataSource"/>

<x-renderer.heading level=5>Pivot by hr_timesheet_employee_date</x-renderer.heading>
<x-renderer.report.pivot-table key="hr_timesheet_employee_date" :dataSource="$dataSource"/>

<x-renderer.heading level=5>Pivot by hr_timesheet_employee_project</x-renderer.heading>
<x-renderer.report.pivot-table key="hr_timesheet_employee_project" :dataSource="$dataSource"/>

<x-renderer.heading level=5>Pivot by hr_timesheet_project_date</x-renderer.heading>
<x-renderer.report.pivot-table key="hr_timesheet_project_date" :dataSource="$dataSource"/>

<x-renderer.heading level=5>Pivot by hr_timesheet_team_date</x-renderer.heading>
<x-renderer.report.pivot-table key="hr_timesheet_team_date" :dataSource="$dataSource"/>
