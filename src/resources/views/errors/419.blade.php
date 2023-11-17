@php
$minutes = env("SESSION_LIFETIME");  
$message = "There is no activity in $minutes minutes.";
$message .= "<br/>For security purposes, you need to login again.";
$message .= "<br/>Redirecting to the Login Page...";
@endphp

<x-feedback.result
    type="warning"
    title="Session Expires"    
    message="{!! $message !!}"
></x-feedback.result>

<script>setTimeout(() => {window.location.href = "/";}, 100);</script>