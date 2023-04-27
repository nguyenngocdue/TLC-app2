<div class="grid grid-cols-12 gap-3">
    <div class="col-span-12 lg:col-span-4">
        <x-dashboards.my-view title="Assigned to Me" viewType="assigned_to_me" />
    </div>
    <div class="col-span-12 lg:col-span-4">
        <x-dashboards.my-view title="Created by Me" viewType="created_by_me" />
    </div>
    <div class="col-span-12 lg:col-span-4">
        <x-dashboards.my-view title=" Monitored by Me" viewType="monitored_by_me" />
    </div>
</div>