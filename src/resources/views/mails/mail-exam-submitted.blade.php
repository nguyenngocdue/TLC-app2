<x-mail::message>
# Dear {{$name}}, 
You have submitted the exam: {{$exam_name}}
<x-mail::button :url="$url">
    View Document
</x-mail::button>
Best Regard,<br>
{{ config('app.name') }}
</x-mail::message>
