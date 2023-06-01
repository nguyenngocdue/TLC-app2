<x-renderer.card title="Filters">
    <div class="grid grid-cols-12">
        <div class="col-span-12 2xl:col-span-5">Project</div>
        <div class="col-span-12 2xl:col-span-7"><x-controls.has-data-source.dropdown2 action="edit" type="hr_timesheet_lines" name="project_id" selected="5" /></div>
        <div class="col-span-12 2xl:col-span-5">Sub Project</div>
        <div class="col-span-12 2xl:col-span-7"><x-controls.has-data-source.dropdown2 action="edit" type="hr_timesheet_lines" name="project_id" selected="5" /></div>
        <div class="col-span-12 2xl:col-span-5">LOD</div> 
        <div class="col-span-12 2xl:col-span-7"><x-controls.has-data-source.dropdown2 action="edit" type="hr_timesheet_lines" name="project_id" selected="5" /></div>
    </div>
</x-renderer.card>