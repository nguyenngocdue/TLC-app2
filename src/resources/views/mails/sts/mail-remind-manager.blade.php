<x-mail::message>
# Weekly staff timesheet outstanding report

Dear {{$user->name}}, this is a reminder that the following staff timesheet is still outstanding:

<x-mail::table>
    |**Check Points** |  <center>Content</center>   | **Creator**    |
    | :-------------  |:--------------------------  |:-------------  |
    @foreach($items as $key => $value)
    |{{$value['a']}}|{{$value['b']}}|{{$value['c']}}|
    @endforeach
</x-mail::table>

<x-mail::button :url="$url">
    Open Staff Timesheet
</x-mail::button>

Best Regard,<br>
{{ config('app.name') }}
</x-mail::message>
