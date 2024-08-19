<x-mail::message>
# Weekly Staff Timesheet Status Report

Dear Admin Member,

This is a summary of the managers who received the reminders:

@foreach($userLists as $list)
@php
    $staff_list = $list['staff_list'];
    $user_name = $list['user_name'];
    $def_assignee = $list['def_assignee'];
    $url = $list['url'];
@endphp

{{$user_name}}'s Team':

@if($user_name != $def_assignee)
(You received this email because you are a proxy approver for {{$def_assignee}})
@endif

<x-mail::table>
    |**Team Member** |**Status** |
    | :------------- |:--------|
    @foreach($staff_list as $key => $value)
    |{{$value['staff_name']}}|{!!$value['linkStr']!!}|
    @endforeach
</x-mail::table>

<x-mail::button :url="$url">
    Open Staff Timesheet App
</x-mail::button>

@endforeach

As a team leader, you are required to complete the approval process before the deadline: 23:59:59 on {{$deadline}}.

Failure to approve these timesheets on time will result in a deduction of your merit points.

Best Regard,

{{ config('app.name') }}
</x-mail::message>
