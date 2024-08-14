<x-mail::message>
# Staff Timesheet Submission Reminder

Dear {{$user_name}},

This is a friendly reminder to submit your timesheet for this week.

<x-mail::button :url="$url">
    Open Staff Timesheet App
</x-mail::button>

As per company policy, the deadline is 23:59:59 on {{$deadline}}.

Failing to submit your timesheet on time will result in deducting your merit points.

To unsubscribe from this reminder, please uncheck it in <a href="{{route("me.index")}}">My Profile</a>.

Best Regard,

{{ config('app.name') }}
</x-mail::message>
