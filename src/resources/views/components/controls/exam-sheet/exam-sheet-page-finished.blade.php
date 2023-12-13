@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

@if ($sheet->owner_id === $cuid) 
   <x-feedback.result type="success" title="Thank you" message="Thank you for your respond." />
@else 
    <x-feedback.result type="error" title="Access denied" message="You can't view a submitted exam sheet." />
@endif

@endphp
@endsection 