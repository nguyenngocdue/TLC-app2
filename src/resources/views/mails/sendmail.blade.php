@component('mail::message')
    # Hello {{ $data['email'] }}

    {{ $data['content'] }}

    Thanks<br>
@endcomponent
