<div id='calendar'></div>
<x-calendar.modal-click modalId="{{$modalId}}"/>
<x-calendar.modal-click-right />
<script type="text/javascript">
    const modalId = @json($modalId);
    const readOnly = @json($readOnly);
    const arrHidden = @json($arrHidden);
    const modal = $(`#`+modalId);
    const containerEl = document.getElementById('task_id');
    const calendarEl = document.getElementById('calendar');
    const checkbox = document.getElementById('drop-remove');
    const modalClickRight = $(`#modal-click-right`);
    const modalTitleTaskValue = $(`#title_task_value`);
    const modalTask = $(`#task_id_1`);
    const modalSubTask = $(`#sub_task_id`);
    const modalWorkMode = $(`#work_mode_id`);
    const modalRemark = $(`#remark`);
    let calendar = null;
    const timesheetableType = @json($timesheetableType);
    const timesheetableId = @json($timesheetableId);
    const apiUrl = @json($apiUrl);
    const token = @json($token);
    modalContainer = document.querySelector("[modal-container]");
    let events = [];
    $(document).click(function(event) {
        var target = $(event.target);
        if (!target.is("#modal-click-right")) {
            modalClickRight.addClass("hidden");
        }
    });
    callApiGetEvents(timesheetableId,apiUrl);
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
                    events = response.hits.data
                    var Calendar = FullCalendar.Calendar;
                    var Draggable = FullCalendar.Interaction.Draggable;
                    new Draggable(containerEl, {
                    itemSelector: '.fc-event',
                    eventData: function(eventEl) {
                            return {
                                title: eventEl.innerText
                            };
                        }
                    });
                    calendar = new Calendar(calendarEl, {
                        headerToolbar: {
                        left:'',     //'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                        },
                        views:{
                            week:{
                                allDaySlot: false,
                            }
                        },
                        timeZone: 'local',
                        initialDate: response.meta,
                        height: 850,
                        contentHeight: 830,
                        aspectRatio: 2,
                        initialView: 'timeGridWeek',
                        locale: 'en',
                        firstDay: 1,
                        dayMaxEvents: true,
                        weekNumbers: true,
                        editable: !readOnly,
                        droppable: !readOnly,
                        events: events,
                        hiddenDays: arrHidden,
                        eventClick: function(info){
                            if(!readOnly){
                                //indentify location modal
                                var {clientX , clientY} = info.jsEvent;
                                var modalTop = clientY / 2;
                                var modalLeft = clientX;
                                modal.css({ top: modalTop, left: modalLeft }).removeClass('hidden');
                                //handle modal
                                handleUpdateModalEvent(info);
                                //extended value render modal
                                var extendedProps = info.event._def.extendedProps;
                                var taskId = extendedProps.task_id;
                                var subTaskId = extendedProps.sub_task_id;
                                var workModeId =    extendedProps.work_mode_id;
                                var remarkValue = extendedProps.remark;
                                //render modal trigger
                                modalTitleTaskValue.text(`Task: ${extendedProps.title_default}`);
                                modalTask.val(taskId)
                                modalTask.trigger('change');
                                modalSubTask.val(subTaskId);
                                modalSubTask.trigger('change');
                                modalWorkMode.val(workModeId);
                                modalWorkMode.trigger('change');
                                modalRemark.val(remarkValue);
                            }
                        },
                        eventDidMount: function(info) {
                            if(!readOnly){
                            //handle click mouse right
                            info.el.addEventListener('contextmenu', function(e) {
                            e.preventDefault();
                            handleContextMenu(info);
                            });
                            }
                            
                        },
                        eventReceive: function(info) {
                            if(!readOnly){
                                var projectId = document.getElementById('project_id').value;
                                var subProjectId = document.getElementById('sub_project_id').value;
                                var lodId = document.getElementById('lod_id').value;
                                var disciplineId = document.getElementById('discipline_id').value;
                                var dateTime = info.event.startStr;
                                var draggedElDiv = info.draggedEl;
                                var taskId = draggedElDiv.children[0].getAttribute('id');
                                switch (info.view.type) {
                                    case 'dayGridMonth':
                                        info.event.remove();
                                        toastr.warning(`Can't create new timesheet line by Month view, please use Week or Day view instead.`);
                                        break;
                                    default:
                                    var data = {
                                    "project_id" : projectId,
                                    "sub_project_id": subProjectId,
                                    "lod_id": lodId,
                                    "discipline_id": disciplineId,
                                    "task_id": taskId,
                                    "date_time": dateTime,
                                    // "all_day": null,
                                    "timesheetable_type": timesheetableType,
                                    "timesheetable_id": timesheetableId
                                }
                                    callApi('post',url,data,info,function(info,calendar,response){
                                        if(response.data){
                                        info.event.remove();
                                        calendar.addEvent(response.data)
                                        toastr.success('Created new timesheet line successfully!');
                                        }
                                    },calendar);
                                        break;
                                }
                            }else{
                                info.event.remove();
                            }
                        },
                        eventDrop: function(info) {
                            if(!readOnly){
                                eventUpdateCalendar(info);
                            }
                        },
                        eventResize: function(info) {
                            if(!readOnly){
                                eventUpdateCalendar(info);
                            }
                        },
                        eventContent: function(info) {
                                var timeText = info.timeText;
                                var eventTitle = info.event.title;
                                var tagSubProject = info.event.extendedProps.tag_sub_project;
                                var eventTitleHTML = '<div class="event-title w-full"><div class="flex justify-between"><div>' + timeText +'</div>'+tagSubProject+'</div>' + eventTitle +'</div>';
                                return { html: eventTitleHTML };
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
        var duplicateButton = modalClickRight.find('.duplicate-button');
        var setMorningButton = modalClickRight.find('.set-morning-button');
        var setAfternoonButton = modalClickRight.find('.set-afternoon-button');
        deleteButton.attr('value', info.event.id);
        duplicateButton.attr('value', info.event.id);
        setMorningButton.attr('value', info.event.id);
        setAfternoonButton.attr('value', info.event.id);
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
            var event = calendar.getEventById(timesheetLineId);
            callApi('patch',url,data,null,function(event,response){
                event.setExtendedProp('work_mode_id',response.data.work_mode_id);
                event.setExtendedProp('remark',response.data.remark);
                event.setExtendedProp('sub_task_id',response.data.sub_task_id);
                event.setProp('backgroundColor',response.data.color);
                event.setProp('title',response.data.title);
                toastr.success('Update data timesheet line successfully!');
                modal.addClass('hidden')
        }, null, event,modal);
        }else{
            toastr.warning(`Please check Timesheet line ID in the modal is nullable or empty`);
        }
    }
    function setTimeEvent(button,type){
        var timesheetLineId = button.value;
        const url = `${apiUrl}/${timesheetLineId}`;
        if(timesheetLineId){
            var event = calendar.getEventById(timesheetLineId);
            switch (type) {
                case 'morning':
                    data = {
                        'start_time': event.startStr,
                        'time_type' : 'morning',
                        'user_id' : event.extendedProps.user_id,
                    }
                    break;
                case 'afternoon':
                    data = {
                        'start_time': event.startStr,
                        'time_type' : 'afternoon',
                        'user_id' : event.extendedProps.user_id,
                    }
                    break;
                default:
                    break;
            }
            callApi('patch',url,data, event,function(info,calendar,response){
                if(response.data){
                    event.remove();
                    calendar.addEvent(response.data)
                    toastr.success('Set time for timesheet line successfully!');
                    modalClickRight.addClass('hidden')
                }
        }, calendar, null,modalClickRight);
        }else{
            toastr.warning(`Please check Timesheet line ID in the modal is nullable or empty`);
        }
    }
    function deleteEvent(button){
        var timesheetLineId = button.value;
        var url = `${apiUrl}/${timesheetLineId}`;
        var event = calendar.getEventById(timesheetLineId);
        callApi('delete',url,null,null,function(event,response){
            toastr.success('Deleted timesheet line successfully!');
            event.remove();
            modalClickRight.addClass('hidden');
        },null, event,modalClickRight);
    }
    function duplicateEvent(button){
        var timesheetLineId = button.value;
        var url = `${apiUrl}_duplicate/${timesheetLineId}`;
        callApi('get',url,null,null,function(calendar,response){
            toastr.success('Duplicated timesheet line successfully!');
            calendar.addEvent(response.data);
            modalClickRight.addClass('hidden');
        },null, calendar,modalClickRight);
    }
    function callApi(type = 'get', url ,data = [] ,info = null, callback= null,
    calendar = null,event = null,modal =null){
        $.ajax({
            type: type,
            url: url,
            headers: {
                'Authorization': 'Bearer ' + token,
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            data : data,
            success: (response) =>{
                if(callback){
                    if(event){
                        callback(event,response,modal)
                    }else if(calendar){
                        callback(info,calendar ,response);
                    }else{
                        callback();
                    }
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
            callApi('patch',url,data,info,function(){
                toastr.success('Updated timesheet line successfully!');
            });
        }else{
            info.revert();
        }
    }
</script>


