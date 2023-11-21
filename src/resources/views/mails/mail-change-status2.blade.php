<x-mail::message>
# Dear {{$name}}, 
@if($diff['status'])
This document change status from 
<x-mail::status>{{$previousValue['status']}}</x-mail::status>
 to 
<x-mail::status>{{$currentValue['status']}}</x-mail::status>
<br>
@endif
@if($diff['bic_assignee_uid'])
This document change assignee from 
<x-mail::status>{{$previousValue['bic_assignee_name']}}</x-mail::status>
 to 
<x-mail::status>{{$currentValue['bic_assignee_name']}}</x-mail::status>
<br>
@endif
@if($diff['bic_monitors_uids'])
This document change monitors from 
<x-mail::status>{{$previousValue['bic_monitors_names']}}</x-mail::status> 
 to   
<x-mail::status>{{$currentValue['bic_monitors_names']}}</x-mail::status>
<br>
@endif
<x-mail::button :url="$action">
    View Document
</x-mail::button>

Best Regard,<br>
{{ config('app.name') }}
</x-mail::message>
