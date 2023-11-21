<x-mail::message>
# Dear {{$name}}, 
@if($isChangeStatus)
This document change status from 
<x-mail::status>{{$oldStatus}}</x-mail::status>
 to 
<x-mail::status>{{$newStatus}}</x-mail::status>
<br>
@endif
@if($changeAssignee)
This document change assignee from 
<x-mail::status>{{$changeAssignee['previous']}}</x-mail::status>
 to 
<x-mail::status>{{$changeAssignee['current']}}</x-mail::status>
<br>
@endif
@if($changeMonitor)
This document change monitors from 
<x-mail::status>{{$changeMonitor['previous']}}</x-mail::status> 
 to   
<x-mail::status>{{$changeMonitor['current']}}</x-mail::status>
<br>
@endif
<x-mail::button :url="$action">
    View Document
</x-mail::button>

Best Regard,<br>
{{ config('app.name') }}
</x-mail::message>
