<x-calendar.navigation-calendar timesheetId="{{$timesheetableId}}" :owner="$owner" />
<div id='calendar' component="full-calendar-renderer"></div>
<x-calendar.modal-click modalId="{{$modalId}}" />
<x-calendar.modal-click-right />

<script type="text/javascript">
    const modalId = @json($modalId);
    const readOnly = @json($readOnly);
    const arrHidden = @json($arrHidden);
    const suffix = @json($suffix);
    const timesheetableType = @json($timesheetableType);
    const timesheetableId = @json($timesheetableId);
    const apiUrl = @json($apiUrl);
    const token = @json($token);
    const timeBreaks = @json($timeBreaks);
</script>
<script src="{{ asset('js/components/FullCalendarEdit-20240815.js') }}"></script>
<style type="text/css">
    .fc-event-main {
        overflow: hidden;
    }
    .fc-timegrid-slots table tbody tr:nth-child(even){
        background-color: #efefef;
        border-radius: 0;
    }
    .fc .fc-scrollgrid tbody > tr:last-child td:last-child {
        border-radius: 0;
    }
    .fc .fc-scrollgrid tbody > tr:last-child td:first-child {
        border-radius: 0;
    }
    .fc .fc-scrollgrid {
        border-radius: 0;
    }
    /* This is for slot 00:15:00 */
    /* .fc .fc-timegrid-slot {
        height: 1rem;
        font-size: 0.4rem;
    } */
</style>