@extends('layouts.app')

@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

<div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="w-full max-w-4xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="bg-gray-200 text-gray-700 text-lg px-6 py-4">
            <span>User Sign-In History</span>
        </div>
        <div class="p-6">
            <iframe 
                src="http://localhost:3000/d-solo/d15e47b4-d7c6-4a77-ac69-c11393e1a0c4/user-sign-in-history?orgId=1&from=1699661136000&to=1701917224000&panelId=10" 
                width="100%" 
                height="500" 
                frameborder="0">
            </iframe>
        </div>
    </div>
</div>

@endsection
