<div class="flex overflow-x-auto justify-center">
    @foreach($tss as $ts)
        @php
            $isCurrent = $timesheetId == $ts->id;            
            $bgColor = $isCurrent ? "bg-gray-800" : "bg-gray-200";
            $textColor = $isCurrent ? "text-gray-200" : "text-gray-800";

            $date = new DateTime($ts->week);
            $weekYear = $date->format('o'); // This gives the correct year according to ISO-8601
            $weekYear = substr($weekYear, -2);
            $weekStr = $date->format("W/") . $weekYear;
        @endphp
        <a class="whitespace-nowrap border rounded px-2 py-1 mx-1 my-2 {{$bgColor}} {{$textColor}}" 
            {{-- @if($ts->id != $timesheetId) --}}
            href="{{route("hr_timesheet_officers.edit",$ts->id)}}"
            {{-- @endif --}}
            >
            W.{{$weekStr}}
        </a>
    @endforeach
</div>