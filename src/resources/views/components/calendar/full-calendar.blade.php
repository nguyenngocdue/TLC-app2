<div id='calendar'></div>
<x-calendar.modal-click modalId="{{$modalId}}"/>
<x-calendar.modal-click-right />
<script type="text/javascript">
    const modalId = @json($modalId);
    const id = 1;
    const modal = $(`#`+modalId);
    const modalClickRight = $(`#modal-click-right`);
    const modalTitleTaskValue = $(`#title_task_value`);
    const modalSubTask = $(`#sub_task_id`);
    const modalWorkMode = $(`#work_mode_id`);
    const modalRemark = $(`#remark`);
    const timesheetableType = @json($timesheetableType);
    const timesheetableId = @json($timesheetableId);
    const apiUrl = @json($apiUrl);
    const token = @json($token);
    modalContainer = document.querySelector("[modal-container]");
    let events = [];
    $(document).click(function(event) {
        var target = $(event.target);
        if (!target.is("#modal-click-right") && !target.closest("#modal-click-right").length) {
            modalClickRight.addClass("hidden");
        }
    });
    callApiGetEvents(id,apiUrl);
    function callApiGetEvents(id,url){
            $.ajax({
            type: 'get',
            url: `${url}/${id}`,
            headers: {
                'Authorization': 'Bearer ' + token,
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
                        left:'today',     //'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                        },
                        height: 850,
                        contentHeight: 830,
                        aspectRatio: 1.5,
                        initialView: 'timeGridWeek',
                        locale: 'en',
                        firstDay: 1,
                        theme: 'sandstone',
                        weekNumbers: true,
                        editable: true,
                        droppable: true,
                        events: events,
                        eventClick: function(info){
                            //indentify location modal
                            var {clientX , clientY} = info.jsEvent;
                            var modalTop = clientY / 2;
                            var modalLeft = clientX;
                            modal.css({ top: modalTop, left: modalLeft }).removeClass('hidden');
                            //handle modal
                            handleUpdateModalEvent(info);
                            //extended value render modal
                            var extendedProps = info.event._def.extendedProps;
                            var subTaskId = extendedProps.sub_task_id;
                            var workModeId =    extendedProps.work_mode_id;
                            var remarkValue = extendedProps.remark;
                            //render modal trigger
                            modalTitleTaskValue.text(`Task : ${info.event.title}`);
                            modalSubTask.val(subTaskId);
                            modalSubTask.trigger('change');
                            modalWorkMode.val(workModeId);
                            modalWorkMode.trigger('change');
                            modalRemark.val(remarkValue);

                        },
                        eventDidMount: function(info) {
                            //handle click mouse right
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
                            var dateTime = info.dateStr;
                            var draggedElDiv = info.draggedEl;
                            var taskId = draggedElDiv.children[0].getAttribute('id');
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
                            callApi('post',url,data,info,callback());
                        },
                        eventDrop: function(info) {
                            eventUpdateCalendar(info);
                        },
                        eventResize: function(info) {
                            eventUpdateCalendar(info);
                        }
                    })
                    calendar.render();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {},
        })
    }
    function handleContextMenu(info) {
        var rect = info.el.getBoundingClientRect();
        var x = rect.top;
        var y = rect.left + 200;
        modalClickRight.css({ top: x, left: y }).removeClass('hidden');
        var deleteButton = modalClickRight.find('.delete-button');
        deleteButton.attr('value', info.event.id);
    }
    function closeModalEvent(){
        modal.addClass('hidden');
    }
    function handleUpdateModalEvent(info) {
        var updateModalButton = modal.find('.update-modal-button');
        updateModalButton.attr('value', info.event.id);
    }
    function updateModalEvent(button){
        var timesheetLineId = button.value;
        var data = {
            'sub_task_id' : modalSubTask.val(),
            'work_mode_id': modalWorkMode.val(),
            'remark': modalRemark.val(),
        }
        const url = `${apiUrl}/${timesheetLineId}`;
        if(timesheetLineId){
            callApi('patch',url,data,null,callback());
        }else{
            toastr.warning(`Please check Timesheet line ID in the modal is nullable or empty`);
        }
    }
    function callback(){
        window.location.reload();
    }
    function deleteEvent(button){
        var timesheetLineId = button.value;
        var url = `${apiUrl}/${timesheetLineId}`;
        callApi('delete',url,null,null,callback());
    }
    function callApi(type = 'get', url ,data = [] ,info = null, callback){
        $.ajax({
            type: type,
            url: url,
            headers: {
                'Authorization': 'Bearer ' + token,
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            data : data,
            success: (response) =>{
                if(response.success){
                    if(callback){
                        callback();
                    }
                    toastr.success(response.message);
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
        var timesheetLineId = info.event.id;
        var data = dataUpdate(info);
        const url = `${apiUrl}/${timesheetLineId}`;
        if(timesheetLineId){
            callApi('patch',url,data,info);
        }else{
            info.revert();
        }
    }
</script>


