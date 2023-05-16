@props(['projectId' => null, 'table' => null])
<div class="grid grid-cols-12 gap-3">
    <div class="col-span-12 lg:col-span-6">
        <x-dashboards.my-view title="Created by Me" viewType="created_by_me" table="{{$table}}" projectId="{{$projectId}}" />
    </div>
    <div class="col-span-12 lg:col-span-6">
        <x-dashboards.my-view title="Assigned to Me" viewType="assigned_to_me" table="{{$table}}" projectId="{{$projectId}}" />
    </div>
</div>