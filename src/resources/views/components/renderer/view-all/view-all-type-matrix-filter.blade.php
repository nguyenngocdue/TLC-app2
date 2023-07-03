@switch($type)

@case('hr_timesheet_workers') 
<x-renderer.view-all.view-all-type-matrix-filter-week-month :type="$type" :dataSource="$dataSource" :viewportParams="$viewportParams"/>
@break

@case('qaqc_wirs') 
<x-renderer.view-all.view-all-type-matrix-filter-project-subproject :type="$type" :dataSource="$dataSource" :viewportParams="$viewportParams"/>
@break

@default
Unknown type {{$type}} in type matrix filter
@break

@endswitch
