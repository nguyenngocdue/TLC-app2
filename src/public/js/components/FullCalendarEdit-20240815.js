const modal = $(`#` + modalId)
const containerEl = document.getElementById(`task_id` + suffix)
const calendarEl = document.getElementById('calendar')
const checkbox = document.getElementById('drop-remove')
const modalClickRight = $(`#modal-click-right`)
const modalTitleTaskValue = $(`#title_task_value`)
const modalProject = $(`#project_id`)
const modalSubProject = $(`#sub_project_id`)
const modalLOD = $(`#lod_id`)
const modalDiscipline = $(`#discipline_id`)
const modalTask = $(`#task_id`)
const modalSubTask = $(`#sub_task_id`)
const modalWorkMode = $(`#work_mode_id`)
const modalRemark = $(`#remark`)
let calendar = null
modalContainer = document.querySelector('[modal-container]')
let events = []
$(document).click(function (event) {
    var target = $(event.target)
    if (!target.is('#modal-click-right')) {
        modalClickRight.addClass('hidden')
    }
})
callApiGetEvents(timesheetableId, apiUrl)

function callApiGetEvents(id, url) {
    $.ajax({
        type: 'get',
        url: `${url}/${id}`,
        headers: {
            Authorization: 'Bearer ' + token,
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        success: function (response) {
            if (response) {
                events = response.hits.data
                var Calendar = FullCalendar.Calendar
                var Draggable = FullCalendar.Interaction.Draggable
                new Draggable(containerEl, {
                    itemSelector: '.fc-event',
                    eventData: function (eventEl) {
                        return {
                            title: eventEl.innerText,
                        }
                    },
                })
                calendar = new Calendar(calendarEl, {
                    headerToolbar: {
                        left: '', //'prev,next today',
                        center: 'title',
                        right: 'timeGridWeek,listWeek',
                        // right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
                    },
                    views: {
                        week: {
                            allDaySlot: false,
                        },
                    },
                    timeZone: 'local',
                    initialDate: response.meta,
                    height: 900,
                    // contentHeight: 830,
                    scrollTime: '06:30:00',
                    aspectRatio: 2,
                    initialView: 'timeGridWeek',
                    locale: 'en',
                    firstDay: 1,
                    // slotDuration: '00:15:00',
                    eventOverlap: false,
                    dayMaxEvents: true,
                    weekNumbers: true,
                    editable: !readOnly,
                    droppable: !readOnly,
                    events: events,
                    hiddenDays: arrHidden,
                    eventClick: function (info) {
                        var extendedProps = info.event._def.extendedProps
                        if (extendedProps.is_ph_or_la == true) return
                        if (!readOnly) {
                            //indentify location modal
                            var { clientX, clientY } = info.jsEvent
                            var modalTop = clientY / 2
                            var modalLeft = clientX
                            modal
                                .css({
                                    top: modalTop,
                                    left: modalLeft,
                                })
                                .removeClass('hidden')
                            //handle modal
                            handleUpdateModalEvent(info)
                            //extended value render modal
                            var projectId = extendedProps.project_id
                            var subProjectId = extendedProps.sub_project_id
                            var lodId = extendedProps.lod_id
                            var disciplineId = extendedProps.discipline_id
                            var taskId = extendedProps.task_id
                            var subTaskId = extendedProps.sub_task_id
                            var workModeId = extendedProps.work_mode_id
                            var remarkValue = extendedProps.remark
                            //render modal trigger
                            modalTitleTaskValue.text(`Task Detail`) //: ${extendedProps.title_default}`);
                            modalProject.val(projectId)
                            modalProject.trigger('change')
                            modalSubProject.val(subProjectId)
                            modalSubProject.trigger('change')
                            modalLOD.val(lodId)
                            modalLOD.trigger('change')
                            modalDiscipline.val(disciplineId)
                            modalDiscipline.trigger('change')
                            modalTask.val(taskId)
                            modalTask.trigger('change')
                            modalSubTask.val(subTaskId)
                            modalSubTask.trigger('change')
                            modalWorkMode.val(workModeId)
                            modalWorkMode.trigger('change')
                            modalRemark.val(remarkValue)
                        }
                    },
                    eventDidMount: function (info) {
                        var extendedProps = info.event._def.extendedProps
                        if (extendedProps.is_ph_or_la == true) return
                        if (!readOnly) {
                            //handle click mouse right
                            info.el.addEventListener('contextmenu', function (e) {
                                e.preventDefault()
                                handleContextMenu(info)
                            })
                        }
                    },
                    eventReceive: function (info) {
                        if (!readOnly) {
                            var projectId = document.getElementById('project_id' + suffix).value
                            var subProjectId = document.getElementById('sub_project_id' + suffix).value
                            var lodId = document.getElementById('lod_id' + suffix).value
                            var disciplineId = document.getElementById('discipline_id' + suffix).value
                            var workModeId = document.getElementById('work_mode_id' + suffix).value

                            var dateTime = info.event.startStr
                            var draggedElDiv = info.draggedEl
                            var taskId = draggedElDiv.children[0].getAttribute('id')
                            var subTaskId = draggedElDiv.children[0].getAttribute('sub-task-id')
                            switch (info.view.type) {
                                case 'dayGridMonth':
                                    info.event.remove()
                                    toastr.warning(`Can't create new timesheet line by Month view, please use Week or Day view instead.`)
                                    break
                                default:
                                    var data = {
                                        project_id: projectId,
                                        sub_project_id: subProjectId,
                                        lod_id: lodId,
                                        discipline_id: disciplineId,
                                        task_id: taskId,
                                        sub_task_id: subTaskId,
                                        date_time: dateTime,
                                        work_mode_id: workModeId,
                                        // "all_day": null,
                                        // "timesheetable_type": timesheetableType,
                                        // "timesheetable_id": timesheetableId
                                        hr_timesheet_officer_id: timesheetableId,
                                    }
                                    console.log(data)
                                    callApi(
                                        'post',
                                        url,
                                        data,
                                        info,
                                        function (info, calendar, response) {
                                            if (response.data) {
                                                info.event.remove()
                                                calendar.addEvent(response.data)
                                                toastr.success('Created new timesheet line successfully!')
                                            }
                                        },
                                        calendar,
                                    )
                                    break
                            }
                        } else {
                            info.event.remove()
                        }
                    },
                    eventDrop: function (info) {
                        if (!readOnly) {
                            eventUpdateCalendar(info)
                        }
                    },
                    eventResize: function (info) {
                        if (!readOnly) {
                            eventUpdateCalendar(info)
                        }
                    },
                    eventContent: function (info) {
                        var timeText = info.timeText
                        var eventTitle = info.event.title
                        var eventSubTitle = info.event.extendedProps.sub_title || ''
                        var tagSubProject = info.event.extendedProps.tag_sub_project || ''
                        var tagPhase = info.event.extendedProps.tag_phase || ''
                        // var nameProject = info.event.extendedProps.name_project || "";
                        var remark = info.event.extendedProps.remark ? 'Remark: ' + info.event.extendedProps.remark : ''
                        let title = ''
                        if (eventTitle == eventSubTitle) {
                            title = `${eventTitle}\n${remark}`
                        } else {
                            title = `${eventTitle}\n${eventSubTitle}\n${remark}`
                        }
                        if (info.event.id) title += `\n#${info.event.id}`
                        var eventTitleHTML = `
                        <div class="event-title w-full" title="${title}">
                            <div class="flex items-baseline justify-between" title="${title}">
                                <div class="" style="font-size:0.65rem">${timeText}</div>
                                    <div class='flex items-end justify-between'>
                                        <span  class="bg-green-200 text-green-800 dark:bg-green-800 dark:text-green-200 leading-none rounded whitespace-nowrap font-semibold text-xs-vw text-xs mx-0.5 px-2 py-1 leading-7 ">
                                            ${tagSubProject}
                                        </span>
                                    </div>
                                </div>
                                <div class="font-bold">
                                ${eventTitle}
                                </div>
                                ${eventTitle == eventSubTitle ? '' : eventSubTitle}
                                <span class='border1 rounded bg-gray-400 block text-center italic'>
                                ${tagPhase}
                                </span>
                                ${remark}                               
                        </div>`
                        return {
                            html: eventTitleHTML,
                        }
                    },
                })
                calendar.render()
            }
            changeBackgroundColorBreakTime()
        },
        error: function (jqXHR, textStatus, errorThrown) {},
    })
}
function changeBackgroundColorBreakTime() {
    var trElements = $('td.fc-timegrid-slot')
    trElements.each(function () {
        var dataTimeValue = $(this).attr('data-time')
        if (timeBreaks) {
            if (dataTimeValue === timeBreaks[0] || dataTimeValue === timeBreaks[1]) {
                $(this).css({ 'background-color': '#777', 'border-radius': 0 })
            }
        }
    })
}

function handleContextMenu(info) {
    var rect = info.el.getBoundingClientRect()
    var x = rect.top
    var y = rect.left + 100
    modalClickRight
        .css({
            top: x,
            left: y,
        })
        .removeClass('hidden')
    var deleteButton = modalClickRight.find('.delete-button')
    var duplicateButton = modalClickRight.find('.duplicate-button')
    var setMorningButton = modalClickRight.find('.set-morning-button')
    var setAfternoonButton = modalClickRight.find('.set-afternoon-button')
    var setFullDayButton = modalClickRight.find('.set-full-day-button')
    deleteButton.attr('value', info.event.id)
    duplicateButton.attr('value', info.event.id)
    setMorningButton.attr('value', info.event.id)
    setAfternoonButton.attr('value', info.event.id)
    setFullDayButton.attr('value', info.event.id)
}

function closeModalEvent() {
    modal.addClass('hidden')
}

function handleUpdateModalEvent(info) {
    var updateModalButton = modal.find('.update-modal-button')
    updateModalButton.attr('value', info.event.id)
}

function updateModalEvent(button) {
    var timesheetLineId = button.value
    var data = {
        project_id: modalProject.val(),
        sub_project_id: modalSubProject.val(),
        lod_id: modalLOD.val(),
        task_id: modalTask.val(),
        sub_task_id: modalSubTask.val(),
        work_mode_id: modalWorkMode.val(),
        remark: modalRemark.val(),
    }
    const url = `${apiUrl}/${timesheetLineId}`
    if (timesheetLineId) {
        var event = calendar.getEventById(timesheetLineId)
        callApi(
            'patch',
            url,
            data,
            null,
            function (event, response) {
                event.setExtendedProp('project_id', response.data.project_id)
                event.setExtendedProp('sub_project_id', response.data.sub_project_id)
                event.setExtendedProp('lod_id', response.data.lod_id)
                event.setExtendedProp('task_id', response.data.task_id)
                event.setExtendedProp('work_mode_id', response.data.work_mode_id)
                event.setExtendedProp('remark', response.data.remark)
                event.setExtendedProp('sub_task_id', response.data.sub_task_id)
                event.setExtendedProp('tag_sub_project', response.data.tag_sub_project)
                event.setExtendedProp('tag_phase', response.data.tag_phase)
                event.setExtendedProp('sub_title', response.data.sub_title)
                event.setProp('backgroundColor', response.data.color)
                event.setProp('title', response.data.title)
                toastr.success('Update data timesheet line successfully!')
                modal.addClass('hidden')
            },
            null,
            event,
            modal,
        )
    } else {
        toastr.warning(`Please check Timesheet line ID in the modal is nullable or empty`)
    }
}

function setTimeEvent(button, type) {
    var timesheetLineId = button.value
    const url = `${apiUrl}/${timesheetLineId}`
    if (timesheetLineId) {
        var event = calendar.getEventById(timesheetLineId)
        data = {
            start_time: event.startStr,
            time_type: type,
            user_id: event.extendedProps.user_id,
            remark: event.extendedProps.remark,
        }
        callApi(
            'patch',
            url,
            data,
            event,
            function (info, calendar, response) {
                if (response.data) {
                    event.remove()
                    if (Array.isArray(response.data)) {
                        response.data.forEach((value) => {
                            calendar.addEvent(value)
                            toastr.success('Set time for timesheet line successfully!')
                        })
                    } else {
                        calendar.addEvent(response.data)
                        toastr.success('Set time for timesheet line successfully!')
                    }
                    modalClickRight.addClass('hidden')
                }
            },
            calendar,
            null,
            modalClickRight,
        )
    } else {
        toastr.warning(`Please check Timesheet line ID in the modal is nullable or empty`)
    }
}

function deleteEvent(button) {
    var timesheetLineId = button.value
    var url = `${apiUrl}/${timesheetLineId}`
    var event = calendar.getEventById(timesheetLineId)
    callApi(
        'delete',
        url,
        null,
        null,
        function (event, response) {
            toastr.success('Deleted timesheet line successfully!')
            event.remove()
            modalClickRight.addClass('hidden')
        },
        null,
        event,
        modalClickRight,
    )
}

function duplicateEvent(button) {
    var timesheetLineId = button.value
    var url = `${apiUrl}_duplicate/${timesheetLineId}`
    callApi(
        'get',
        url,
        null,
        null,
        function (calendar, response) {
            toastr.success('Duplicated timesheet line successfully!')
            calendar.addEvent(response.data)
            modalClickRight.addClass('hidden')
        },
        null,
        calendar,
        modalClickRight,
    )
}

function callApi(type = 'get', url, data = [], info = null, callback = null, calendar = null, event = null, modal = null) {
    $.ajax({
        type: type,
        url: url,
        headers: {
            Authorization: 'Bearer ' + token,
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        data: data,
        success: (response) => {
            if (callback) {
                if (event) {
                    callback(event, response, modal)
                } else if (calendar) {
                    callback(info, calendar, response)
                } else {
                    callback()
                }
            }
        },
        error: (jqXHR, textStatus, errorThrown) => {
            if (info) {
                console.error('Error:', textStatus)
            }
        },
    })
}

function dataUpdate(info) {
    var data = {
        start_time: info.event.start.toISOString(),
        end_time: info.event.end.toISOString(),
    }
    return data
}

function eventUpdateCalendar(info) {
    var timesheetLineId = info.event.id
    var data = dataUpdate(info)
    const url = `${apiUrl}/${timesheetLineId}`
    if (timesheetLineId) {
        callApi('patch', url, data, info, function () {
            toastr.success('Updated timesheet line successfully!')
        })
    } else {
        info.revert()
    }
}
