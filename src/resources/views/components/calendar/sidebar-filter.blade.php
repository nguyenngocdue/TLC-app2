<x-renderer.card title="Filters">
    <div class="grid grid-cols-12 gap-y-2">
        {{-- <div class="col-span-12 2xl:col-span-5">Test</div>
        <div class="col-span-12 2xl:col-span-7">
            <x-modals.parent-type7 name='modal_ot_team' selected1='1003-a' allowClear={{true}}></x-modals.parent-type7>
            <x-modals.parent-id7 name='modal_ot_user1' multiple={{false}} control='radio-or-checkbox2' selected='"2002"'>
            </x-modals.parent-type7>
        </div> --}}
        <div class="col-span-12 2xl:col-span-5">Project</div>
        <div class="col-span-12 2xl:col-span-7">
            <x-calendar.sidebar-filter-project name="modal_project" selected="5"/>
        </div>
        <div class="col-span-12 2xl:col-span-5">Sub Project</div>
        <div class="col-span-12 2xl:col-span-7">
            <x-calendar.sidebar-filter-sub-project name="modal_sub_project" selected="82"/>
        </div>
        <div class="col-span-12 2xl:col-span-5">LOD</div> 
        <div class="col-span-12 2xl:col-span-7">
            {{-- <x-controls.has-data-source.dropdown2 action="edit" type="hr_timesheet_lines" name="project_id" selected="5" /> --}}
        </div>
    </div>
</x-renderer.card>