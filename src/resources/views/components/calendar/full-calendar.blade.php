<div id='calendar' component="full-calendar-renderer"></div>

<div class="flex w-full p-2 gap-2">
    <x-renderer.card title="Summary By Days" class="w-1/2 border border-gray-300 rounded-lg px-2 py-1 flex items-center">
        <div id="summaryHoursByDay" class="border bg-gray-100 p-2 rounded w-full">Loading...</div>        
    </x-renderer.card>
    <x-renderer.card title="Summary By Sub-Projects" class="w-1/2 border border-gray-300 rounded-lg px-2 py-1 flex items-center">
        <div id="summaryHoursBySubProject" class="border bg-gray-100 p-2 rounded w-full">Loading...</div>
    </x-renderer.card>
</div>
    
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
    const hiddenCalendarHeader = @json($hiddenCalendarHeader);
</script>
<script src="{{ asset('js/components/FullCalendarEdit-20240828.js') }}"></script>
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
</style>