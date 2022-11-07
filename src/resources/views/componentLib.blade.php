@extends('layouts.applean')
@section('content')

@php
$theChildComponents = [
'<x-renderer.tag color="slate" value="slate"></x-renderer.tag>',
'
<x-renderer.tag color="slate" value="slate"></x-renderer.tag>
<x-renderer.tag color="zinc" value="zinc" />
<x-renderer.tag color="neutral" value="neutral" />
<x-renderer.tag color="stone" value="stone" />
<x-renderer.tag color="amber" value="amber" />
<x-renderer.tag color="yellow" value="yellow" />
<x-renderer.tag color="lime" value="lime" />
<x-renderer.tag color="emerald" value="emerald" />
<x-renderer.tag color="teal" value="teal" />
<x-renderer.tag color="cyan" value="cyan" />
<x-renderer.tag color="sky" value="sky" />
<x-renderer.tag color="blue" value="blue" />
<x-renderer.tag color="indigo" value="indigo" />
<x-renderer.tag color="violet" value="violet" />
<x-renderer.tag color="purple" value="purple" />
<x-renderer.tag color="fuchsia" value="fuchsia" />
<x-renderer.tag color="pink" value="pink" />
<x-renderer.tag color="rose" value="rose" />
<x-renderer.tag color="green" value="green" />
<x-renderer.tag color="orange" value="orange" />
<x-renderer.tag color="red" value="red" />
<x-renderer.tag color="gray" value="gray" />
<x-renderer.tag />
',
];
@endphp
<x-renderer.card :items="$theChildComponents" />

<br />
<br />

<x-renderer.tag color="slate" value="slate" />
<x-renderer.tag color="zinc" value="zinc" />
<x-renderer.tag color="neutral" value="neutral" />
<x-renderer.tag color="stone" value="stone" />
<x-renderer.tag color="amber" value="amber" />
<x-renderer.tag color="yellow" value="yellow" />
<x-renderer.tag color="lime" value="lime" />
<x-renderer.tag color="emerald" value="emerald" />
<x-renderer.tag color="teal" value="teal" />
<x-renderer.tag color="cyan" value="cyan" />
<x-renderer.tag color="sky" value="sky" />
<x-renderer.tag color="blue" value="blue" />
<x-renderer.tag color="indigo" value="indigo" />
<x-renderer.tag color="violet" value="violet" />
<x-renderer.tag color="purple" value="purple" />
<x-renderer.tag color="fuchsia" value="fuchsia" />
<x-renderer.tag color="pink" value="pink" />
<x-renderer.tag color="rose" value="rose" />
<x-renderer.tag color="green" value="green" />
<x-renderer.tag color="orange" value="orange" />
<x-renderer.tag color="red" value="red" />
<x-renderer.tag color="gray" value="gray" />
<x-renderer.tag />

<br />
<br />
<br />

<x-feedback.alert type="success" title="Success" message="Hello" />
<x-feedback.alert type="info" title="Info" message="Hello" />
<x-feedback.alert type="warning" title="Warning" message="Hello" />
<x-feedback.alert type="error" title="Error" message="Hello" />
<x-feedback.alert />

@endsection