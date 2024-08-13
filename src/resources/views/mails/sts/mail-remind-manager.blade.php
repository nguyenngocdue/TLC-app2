<x-mail::message>
# Weekly Staff Timesheet Status Report

Dear {{$user_name}}, this is the timesheet status of the following staff:

@if($user_name != $def_assignee)
(You received this email because you are a proxy approver of {{$def_assignee}})
@endif

{{-- @php Log::info($staff_list) @endphp --}}
{{-- |{{$value['staff_name']}}| <span style="display: inline;"><x-mail::status>pending_approval</x-mail::status><x-mail::status>new</x-mail::status></span>| --}}
{{-- |{{$value['staff_name']}}|@foreach($value['links'] as $link) <x-mail::status href="{{$link['href']}}">{{$link['status']}}</x-mail::status> @endforeach| --}}

<x-mail::table>
    |**Team Member** |**Link** |
    | :------------- |:--------|
    @foreach($staff_list as $key => $value)
    |{{$value['staff_name']}}|{!!$value['linkStr']!!}|
    @endforeach
</x-mail::table>

<x-mail::button :url="$url">
    Open Staff Timesheet App
</x-mail::button>

As a team leader, you will need to approve the timesheet before the deadline (by 23:59:59 {{$deadline}}).

Best Regard,

{{ config('app.name') }}
</x-mail::message>
