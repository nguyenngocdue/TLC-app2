<x-mail::message>
# Dear {{$name}}, 
A document has been created by {{$name}}

<ul>
    @foreach($meta as $label => $value)
    <li>
        <span>{{$label}}:</span>
        <b>{{$value}}</b>
    </li>
    @endforeach
</ul>

<x-mail::button :url="$url">
    View Document
</x-mail::button>
Best Regard,<br>
{{ config('app.name') }}
</x-mail::message>
