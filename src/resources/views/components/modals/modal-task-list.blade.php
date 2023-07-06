
@extends("modals.modal-large")

@section($modalId.'-header', "Task list")

@section($modalId.'-body')
@php
    // $user = ("App\\Models\\User")::findFromCache($selectedUser);
    // $discipline = $user->discipline;
    // dump($discipline);
@endphp
@endsection

{{-- @section($modalId.'-footer')
@endsection --}}

@section($modalId.'-javascript')
<script>
    
</script>
@endsection