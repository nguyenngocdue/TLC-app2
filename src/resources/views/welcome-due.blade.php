@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

<div class="flex flex-row pr-5">
    <iframe class="ml-80" src="https://grafana.tlcmodular.com/d-solo/e6d647c2-948d-46b6-a0d6-5f444ba8125f/non-conformance-reports?orgId=1&from=1658941200000&to=1706547599000&panelId=2" width="500" height="500" frameborder="0">
    </iframe>
</div>

@endsection
