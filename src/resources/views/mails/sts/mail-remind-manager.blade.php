<x-mail::message>
# Weekly Staff Timesheet Status Report

Dear {{$user_name}},

Please find below the timesheet status for the following team members:

@if($user_name != $def_assignee)
(You received this email because you are a proxy approver for {{$def_assignee}})
@endif

{{-- @php Log::info($staff_list) @endphp --}}
{{-- |{{$value['staff_name']}}| <span style="display: inline;"><x-mail::status>pending_approval</x-mail::status><x-mail::status>new</x-mail::status></span>| --}}
{{-- |{{$value['staff_name']}}|@foreach($value['links'] as $link) <x-mail::status href="{{$link['href']}}">{{$link['status']}}</x-mail::status> @endforeach| --}}

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

As a team leader, you are required to complete the approval process before the deadline: 23:59:59 on {{$deadline}}.

Failure to approve these timesheets on time will result in a deduction of your merit points.

Best Regard,

{{ config('app.name') }}
</x-mail::message>
