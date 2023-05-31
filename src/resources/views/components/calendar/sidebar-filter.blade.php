<x-renderer.card title="Filters">
    <div class="grid grid-cols-12">
        <div class="col-span-12 xl:col-span-6">Project</div>
        <div class="col-span-12 xl:col-span-6"><x-controls.has-data-source.dropdown2 action="edit" type="hr_timesheet_lines" name="project_id" selected="5" /></div>
        <div class="col-span-12 xl:col-span-6">SubProject</div>
        <div class="col-span-12 xl:col-span-6"><x-controls.has-data-source.dropdown2 action="edit" type="hr_timesheet_lines" name="project_id" selected="5" /></div>
        <div class="col-span-12 xl:col-span-6">LOD</div> 
        <div class="col-span-12 xl:col-span-6"><x-controls.has-data-source.dropdown2 action="edit" type="hr_timesheet_lines" name="project_id" selected="5" /></div>
    </div>
</x-renderer.card>