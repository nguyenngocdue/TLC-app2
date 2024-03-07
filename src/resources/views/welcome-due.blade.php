@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

<!-- Button -->
<button id="myButton">Click me</button>

<!-- Display area for message -->
<div id="message"></div>

<!-- Include Toastr CSS and JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

@if(Session::has('toastr_message'))
    <!-- Execute JavaScript to show Toastr -->
    <script>
        toastr.success('{{ Session::get('toastr_message') }}');
    </script>
@endif

<!-- Include Axios library for making AJAX requests -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    document.getElementById('myButton').addEventListener('click', function() {
        axios.post('https://127.0.0.1:38002/diginet/transfer-diginet-data/handle-click-event')
            .then(response => {
                // Update the message display area with the response message
                document.getElementById('message').innerText = response.data.message;
                toastr.success(response.data.message);
            })
            .catch(error => {
                console.error(error);
            });
    });
</script>

@endsection
