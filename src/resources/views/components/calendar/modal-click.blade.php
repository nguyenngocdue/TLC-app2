@props(['modalId'])
<div id="{{$modalId}}" class="hidden fixed flex z-10 items-center justify-center">
    <!-- Modal content -->
    <div class="relative bg-white rounded-lg shadow-lg border-[1.5px] dark:bg-gray-700">
        <!-- Modal header -->
        <div class="items-center justify-between p-2 border-b rounded-t dark:border-gray-600">
            <div class="flex">
                <h3 class="text-lg justify-center font-semibold text-gray-900 dark:text-white" id="title_task_value">
                    Task:
                </h3>
                <button type="button" onclick="closeModalEvent()" class="text-gray-900 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-full text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" >
                    <i class="fa-sharp fa-solid fa-xmark w-6 h-6 text-base"></i>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
        </div>
        <!-- Modal body -->
        <div class="px-2 overflow-y-auto w-96 h-[500px]" modal-container>
                <div class="grid grid-cols-12 mt-2 items-center">
                    <span class="col-span-3 text-right mr-1">Project</span>
                    <div class="col-span-9">
                        <x-calendar.ModalFilterProject tableName="projects" name="project_id" id="project_id" typeToLoadListener="hr_timesheet_line"/>
                    </div>
                </div>
                 <div class="grid grid-cols-12 mt-2 items-center">
                    <span class="col-span-3 text-right mr-1">Sub Project</span>
                    <div class="col-span-9">
                    <x-calendar.ModalFilterSubProject tableName="sub_projects" name="sub_project_id" id="sub_project_id" typeToLoadListener="hr_timesheet_line" />
                    </div>
                </div>
                <div class="grid grid-cols-12 mt-2 items-center">
                    <span class="col-span-3 text-right mr-1">Phase</span>
                    <div class="col-span-9">
                    <x-calendar.ModalFilterLod tableName="pj_task_phases" name="lod_id" id="lod_id" typeToLoadListener="hr_timesheet_line" />
                    </div>
                </div>
                <div class="grid grid-cols-12 mt-2 items-center hidden">
                    <span class="col-span-3 text-right mr-1">Discipline</span>
                    <div class="col-span-9">
                    <x-calendar.ModalFilterDiscipline tableName="user_disciplines" name="discipline_id" id="discipline_id" typeToLoadListener="hr_timesheet_line" />
                    </div>
                </div>
                <div class="grid grid-cols-12 mt-2 items-center">
                    <span class="col-span-3 text-right mr-1">Task</span>
                    <div class="col-span-9">
                    <x-calendar.ModalFilterTask tableName="pj_tasks" name="task_id" typeToLoadListener="hr_timesheet_line" />
                    </div>
                </div>
                <div class="grid grid-cols-12 mt-2 items-center">
                    <span class="col-span-3 text-right mr-1">Sub-Task</span>
                    <div class="col-span-9">
                    <x-calendar.ModalFilterSubTask tableName="pj_sub_tasks" name="sub_task_id" typeToLoadListener="hr_timesheet_line"/>
                    </div>
                </div>
                <div class="grid grid-cols-12 mt-2 items-center">
                    <span class="col-span-3 text-right mr-1">Work Mode</span>
                    <div class="col-span-9">
                    <x-calendar.ModalFilterWorkMode tableName="work_modes" name="work_mode_id"/>
                    </div>
                </div>
                <div class="grid grid-cols-12 m-2 items-center">
                    <span class="col-span-3 text-right mr-1">Remark</span>
                    <div class="col-span-9">
                    <x-controls.textarea2 name="remark" value="" />
                    </div>
                </div>
        </div>
        <!-- Modal footer -->
        <div class="flex items-center justify-end rounded-b border-t border-solid border-slate-200 dark:border-gray-600 p-2">
            <x-renderer.button onClick="closeModalEvent()">Cancel</x-renderer.button>
            <x-renderer.button onClick="updateModalEvent(this)" class="mx-2 update-modal-button" type='success'>Save</x-renderer.button>
        </div> 
    </div>
</div>