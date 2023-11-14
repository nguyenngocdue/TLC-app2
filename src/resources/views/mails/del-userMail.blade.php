{{-- @dd($dataEvent); --}}
@php
$id = $dataEvent->dataEvent['id'];
$type = $dataEvent->dataEvent['type'];
@endphp

<h1>A item has been created successfuly</h1>
<div>
    <span>Id : {{$id}} </span>
    <br />
    <span>Type : {{$type}} </span>

</div>
