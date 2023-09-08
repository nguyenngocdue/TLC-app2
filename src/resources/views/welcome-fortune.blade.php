@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> --}}

<div>
  <form>
    @php
    echo $_GET['test'];
    @endphp
  <input name="test" id="myID"/>
  <button>OK</button>
  </form>
<script>
  // import flatpickr from "flatpickr";
  // const flatpickr = require("flatpickr");
  flatpickr("#myID", {
    enableTime: true,
    altInput: true,
    altFormat: "d/m/Y H:i",
    dateFormat: 'Y-m-d H:i:S',
    weekNumbers: true,
  });
  </script>


</div>
@endsection