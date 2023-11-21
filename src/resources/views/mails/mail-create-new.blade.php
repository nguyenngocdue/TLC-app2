<x-mail::message>
# Dear {{$name}}, 
A document has been created by {{$name}}
<x-mail::button :url="$url">
    View Document
</x-mail::button>
Best Regard,<br>
{{ config('app.name') }}
</x-mail::message>
