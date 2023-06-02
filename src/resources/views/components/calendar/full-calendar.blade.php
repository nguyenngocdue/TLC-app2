<div id='calendar'></div>
<div id="{{$modalId}}" class="hidden fixed flex z-10 items-center justify-center">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="items-center justify-between p-2 border-b rounded-t dark:border-gray-600">
                    <div class="flex">
                        <h3 class="text-lg justify-center font-semibold text-gray-900 dark:text-white">
                            Task: 
                        </h3>
                        <button type="button" onclick="closeModalEvent()" class="text-gray-900 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-full text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="large-modal">
                            <i class="fa-sharp fa-solid fa-xmark w-6 h-6 text-base"></i>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                </div>
                <!-- Modal body -->
                <div class="px-6 overflow-y-auto w-96 h-96" data-container>
                    
                </div>
                <!-- Modal footer -->
                <div class="flex items-center justify-end rounded-b border-t border-solid border-slate-200 dark:border-gray-600 p-2">
                    <x-renderer.button click="onclick='closeModalEvent()'">Cancel</x-renderer.button>
                    <x-renderer.button class="mx-2" type='success'>Save</x-renderer.button>
                </div> 
            </div>
</div>
<div id="modal-click-right" class="hidden fixed flex z-10 items-center justify-center">
    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
        <button onclick="deleteEvent(this)" value="" class="delete-button inline-flex items-center w-full px-5 py-2 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200">
            <span>Delete</span>
            <i class="ml-2 fa fa-trash"></i>
        </button>
    </div>
</div>
<script >
    const modalId = @json($modalId);
    const modal = $(`#`+modalId);
    const modalClickRight = $(`#modal-click-right`);
    const timesheetableType = @json($timesheetableType);
    const timesheetableId = @json($timesheetableId);
    let events = []
    $(document).click(function(event) {
        var target = $(event.target);
        if (!target.is("#modal-click-right") && !target.closest("#modal-click-right").length) {
            modalClickRight.addClass("hidden");
        }
    });
    callApiGetEvents(1); 
    function callApiGetEvents(id){
            $.ajax({
            type: 'get',
            url: `https://dev2.tlcmodular.com/api/v1/hr/timesheet_staff/${id}`,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (response) {
                if(response){
                    events = response.data
                    var Calendar = FullCalendar.Calendar;
                    var Draggable = FullCalendar.Interaction.Draggable;
                    var containerEl = document.getElementById('task_id');
                    var calendarEl = document.getElementById('calendar');
                    var checkbox = document.getElementById('drop-remove');
                    new Draggable(containerEl, {
                    itemSelector: '.fc-event',
                    eventData: function(eventEl) {
                            return {
                                title: eventEl.innerText
                            };
                        }
                    });
                    var calendar = new Calendar(calendarEl, {
                        headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                        },
                        height: 850,
                        contentHeight: 830,
                        aspectRatio: 1.5,
                        initialView: 'timeGridWeek',
                        locale: 'en',
                        theme: 'sandstone',
                        weekNumbers: true,
                        editable: true,
                        droppable: true,
                        events: events,
                        eventClick: function(info){
                            var {clientX , clientY} = info.jsEvent;
                            var modalTop = clientY / 2;
                            var modalLeft = clientX;
                            modal.css({ top: modalTop, left: modalLeft }).removeClass('hidden')
                        },
                        eventDidMount: function(info) {
                            info.el.addEventListener('contextmenu', function(e) {
                            e.preventDefault();
                            handleContextMenu(info);
                            });
                        },
                        drop: function(info) {
                            var projectId = document.getElementById('project_id').value;
                            var subProjectId = document.getElementById('sub_project_id').value;
                            var lodId = document.getElementById('lod_id').value;
                            var disciplineId = document.getElementById('discipline_id').value;
                            var dateTime = info.dateStr
                            var draggedElDiv = info.draggedEl
                            var taskId = draggedElDiv.children[0].getAttribute('id')
                            console.log(lodId);
                            var data = {
                                "project_id" : projectId,
                                "sub_project_id": subProjectId,
                                "lod_id": lodId,
                                "discipline_id": disciplineId,
                                "task_id": taskId,
                                "date_time": dateTime,
                                "timesheetable_type": timesheetableType,
                                "timesheetable_id": timesheetableId
                            }
                            const url = 'https://dev2.tlcmodular.com/api/v1/hr/timesheet_staff'
                            callApi('post',url,data,info,function() {
                                window.location.reload();
                            })
                        },
                        eventDrop: function(info) {
                            eventUpdateCalendar(info)
                        },
                        eventResize: function(info) {
                            eventUpdateCalendar(info)
                        }
                    })
                    calendar.render()
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {},
        })
    }
    function handleContextMenu(info) {
        var rect = info.el.getBoundingClientRect();
        var x = rect.top;
        var y = rect.left + 200;
        modalClickRight.css({ top: x, left: y }).removeClass('hidden')
        var deleteButton = modalClickRight.find('.delete-button');
        deleteButton.attr('value', info.event.id);
    }
    function closeModalEvent(){
        modal.addClass('hidden')
    }
    function deleteEvent(button){
        var timesheetLineId = button.value;
        var url = `https://dev2.tlcmodular.com/api/v1/hr/timesheet_staff/${timesheetLineId}`
        callApi('delete',url,null,null,function() {
            window.location.reload();
        });
    }
    function callApi(type = 'get', url ,data = [] ,info = null, callback){
        $.ajax({
            type: type,
            url: url,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            data : data,
            success: (response) =>{
                if(response){
                    if(callback){
                        callback()
                    }
                    toastr.success(response.message)
                }
            },
            error: (jqXHR, textStatus, errorThrown) => {
                if(info){
                    info.revert();
                }
            },
        })
    }
    function dataUpdate(info){
        var data = {
            'start_time': info.event.start.toISOString(),
            'end_time': info.event.end.toISOString(),
        }
        return data;
    }
    function eventUpdateCalendar(info){
        var timesheetLineId = info.event.id
        var data = dataUpdate(info)
        const url = `https://dev2.tlcmodular.com/api/v1/hr/timesheet_staff/${timesheetLineId}`
        if(timesheetLineId){
            callApi('put',url,data,info)
        }else{
            info.revert();
        }
    }
</script>