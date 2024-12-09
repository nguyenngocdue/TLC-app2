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
@foreach($previousValue['bic_monitors_names'] as $name)
<x-mail::status>{{$name}}</x-mail::status> 
@endforeach
 to 
@foreach($currentValue['bic_monitors_names'] as $name)
<x-mail::status>{{$name}}</x-mail::status>
@endforeach
<br>
@endif

<ul>
    @foreach($meta as $label => $value)
    <li>
        <span>{{$label}}:</span>
        <b>{{$value}}</b>
    </li>
    @endforeach
</ul>

<x-mail::button :url="$action">
    View Document
</x-mail::button>

Best Regard,<br>
{{ config('app.name') }}
</x-mail::message>
