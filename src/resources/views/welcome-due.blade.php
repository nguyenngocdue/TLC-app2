@extends('layouts.app')

@section('topTitle', 'Welcome')
@section('title', '')
@section('content')

<div class="flex justify-center bg-only-print">
    <div class="md:px-4">
        <div style='page-break-after:always!important' class="w-[1400px] min-h-[990px] items-center bg-white box-border p-8">
            <div class="p-6">
                <iframe src="http://localhost:3000/d-solo/c66cae95-21f2-4c84-a0b9-1a2eadd87071/log-access-v2?orgId=1&from=1696266000000&to=1706720399000&var-user_id=35&var-entity_name=dashboard&var-route_name=dashboard.index&var-show_value=true&theme=light&panelId=50" width="1300" height="500" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection
