<script>
import { ref } from 'vue'
import FullCalendar from '@fullcalendar/vue3'
import dayGrid from '@fullcalendar/daygrid'
import list from '@fullcalendar/list'
import timeGrid from '@fullcalendar/timegrid'
import multiMonth from '@fullcalendar/multimonth'
import interaction from '@fullcalendar/interaction'

const id = ref(10)

export default {
    components: {
        FullCalendar,
    },
    data() {
        return {
            calendarOptions: {
                plugins: [dayGrid, timeGrid, list, multiMonth, interaction],
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev, next today',
                    center: 'title',
                    right: 'timeGridDay,timeGridWeek,dayGridMonth,multiMonthYear,listWeek',
                },
                editable: true,
                selectable: true,
                weekends: true,
                events: [
                    {
                        title: 'Business Lunch',
                        start: '2023-05-03T13:00:00',
                        constraint: 'businessHours',
                    },
                    {
                        title: 'Meeting',
                        start: '2023-05-13T11:00:00',
                        constraint: 'availableForMeeting',
                        color: '#257e4a',
                    },
                    {
                        title: 'Conference',
                        start: '2023-05-18T10:00:00',
                        end: '2023-05-18T12:00:00',
                        color: '#257e4a',
                    },
                    {
                        title: 'Party',
                        start: '2023-05-29T20:00:00',
                    },

                    {
                        groupId: 'availableForMeeting',
                        start: '2023-05-11T10:00:00',
                        end: '2023-05-11T16:00:00',
                        display: 'background',
                    },
                    {
                        groupId: 'availableForMeeting',
                        start: '2023-05-13T10:00:00',
                        end: '2023-05-13T16:00:00',
                        display: 'background',
                    },

                    {
                        start: '2023-05-24',
                        end: '2023-05-28',
                        overlap: false,
                        display: 'background',
                        color: '#ff9f89',
                    },
                    {
                        start: '2023-05-06',
                        end: '2023-05-08',
                        overlap: false,
                        display: 'background',
                        color: '#ff9f89',
                    },
                ],

                select: this.handleDateSelect,
                eventClick: this.handleEventClick,
                eventSet: this.handleEvents,
            },
            currentEvents: [],
        }
    },
    methods: {
        handleWeekendsToggle() {
            this.calendarOptions.weekends = !this.calendarOptions.weekends // update a property
        },
        handleDateSelect(arg) {
            id.value++
            let cal = arg.view.calendar
            cal.unselect() // clear date selection
            cal.addEvent({
                id: `${id.value}`,
                title: `New Event ${id.value}`,
                start: arg.startStr,
                end: arg.endStr,
                allDay: arg.allDay,
            })
        },
        handleEventClick(clickInfo) {
            if (
                confirm(
                    `Are you sure you want to delete the event '${clickInfo.event.title}'`
                )
            ) {
                clickInfo.event.remove()
            }
        },
        handleEvents(events) {
            this.currentEvents = events
        },
    },
}
</script>
<template>
    <FullCalendar :options="calendarOptions" />
</template>
