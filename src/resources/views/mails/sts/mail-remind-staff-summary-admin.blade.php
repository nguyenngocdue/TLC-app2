<x-mail::message>
# Staff Timesheet Submission Reminder

Dear Admin Member,

This is the list of users who received the timesheet reminder:

@foreach($userLists as $user)

    - {{App\Models\Workplace::findFromCache($user->current_workplace)->name}} - {{ $user->name }} ({{ $user->email }})
@endforeach

Best Regard,

{{ config('app.name') }}
</x-mail::message>
