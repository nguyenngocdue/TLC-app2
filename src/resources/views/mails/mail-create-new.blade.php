<x-mail::message>
# Dear {{$name}}, 
A document has been created by {{$name}}
<x-mail::button :url="$url">
    View Document
</x-mail::button>
Thank you for using our application!<br>
{{ config('app.name') }} Modular
</x-mail::message>
