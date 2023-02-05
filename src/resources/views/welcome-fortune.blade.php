@extends('layouts.applean')


@section('content')
<br/>

Welcome

<script>
    k = @json($listenerDataSource);
    listenersOfDropdown2 = @json($listeners);
    filtersOfDropdown2 = @json($filters);

</script>

<div class="px-5">
    <form action="" method="POST">
        @csrf
        <button class="hidden" title="This button is to avoid a bug when change label and press enter."></button>
        <x-renderer.table :columns="$tableColumns" :dataSource="$tableDataSource" type={{$type}}/>
        <x-renderer.button htmlType='submit' type='primary'>Update</x-renderer.button>
    </form>
</div>

<br/><br/><br/><br/><br/>

@endsection