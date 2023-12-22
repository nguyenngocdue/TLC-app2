@extends('layouts.app')
@section('topTitle', 'Welcome')
@section('title', '')

@section('content')

<input type="checkbox" id="threeStateCheckbox">
<script>
    const checkbox = document.getElementById('threeStateCheckbox');

// Set initial state to unchecked
let state = 0; // 0 for unchecked, 1 for checked, 2 for indeterminate

checkbox.addEventListener('click', function() {
    state = (state + 1) % 3; // Cycle through 0, 1, 2 states

    if (state === 0) {
        checkbox.checked = false;
        checkbox.indeterminate = false;
    } else if (state === 1) {
        checkbox.checked = true;
        checkbox.indeterminate = false;
    } else {
        checkbox.checked = false;
        checkbox.indeterminate = true;
    }
});

    </script>

<div id="divMain"></div>
<script src="{{ asset('js/editable-table.js') }}"></script>
<script>EditableTableDemo('divMain')</script>

@endsection 