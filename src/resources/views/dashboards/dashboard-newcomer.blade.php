@extends('layouts.app')

@section('topTitle', 'Dashboard')
@section('title', '')

@section('content')
{{-- @dump($viewportParams) --}}
<div class="px-4 min-h-screen">
    <x-elapse title="Boot the layout: "/>
    {{-- <div class="grid grid-cols-12 gap-3 my-5"> --}}
        <div class="w-1/2 mx-auto my-10 border rounded bg-white p-10">
            {{-- <x-dashboards.my-view title="Monitored by Me" viewType="monitored_by_me"  /> --}}
            Welcome <b>{{$cu->email}}</b>,<br/>
            Your account has been created in our system.<br/>
            However, we need to set up your role and permissions to grant you access to the appropriate projects.<br/>
            Please contact our admin: <a class="text-blue-400 hover:text-blue-600" href="mailto:thucvo@tlcmodular.com">thucvo@tlcmodular.com</a> and CC the person who referred you to get your account set up.<br/>
            Thank you!
        </div>
    {{-- </div> --}}
</div>

@endsection