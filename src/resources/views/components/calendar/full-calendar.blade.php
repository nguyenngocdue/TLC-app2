<x-calendar.navigation-calendar timesheetId="{{$timesheetableId}}" :owner="$owner" week="{{}}" />
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
<script src="{{ asset('js/components/FullCalendarEdit.js') }}"></script>
<style type="text/css">
    .fc-event-main {
        overflow: hidden;
    }
</style>