@extends('layouts.app')
@section('topTitle', "")
@section('title', "User Report Settings")
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
 <div class="px-4">
    <div class="grid row-1">
        <div class="grid grid-cols-12 gap-4">
            @foreach($data as $key => $value)
            @php
                $tableDataSource = $value['tableDataSource'];
                $tableColumns = $value['tableColumns'];
                $title = $value['title'];

            @endphp

                        <div class={{$key=='queriedUsers' ? 'col-span-12' : 'col-span-6'}}>
                            <x-renderer.heading level=3 class='text-center py-4 font-bold'>{{$title}}</x-renderer.heading>
                            @if($key=='queriedUsers')
                                @php
                                    $usersNeedToCheck = json_encode($value['usersNeedToCheck']);
                                @endphp
                            <div class="mb-2">
                                <x-renderer.button htmlType="submit" click="updateRpInUserSetting({{$usersNeedToCheck}}, '{{$routeUpdate}}', '{{$token}}')" type="secondary"><i
                                        class="fa-sharp fa-solid fa-circle-x pr-1"></i>Update User Setting</x-renderer.button>
                            </div>
                            @endif
                            <x-renderer.table 
                                showNo={{ true }}  
                                :columns="$tableColumns" 
                                :dataSource="$tableDataSource"
                                maxH="480"
                                tableTrueWidth={{false}} 
                                page-limit="10000" 
                                showPaginationTop="true"
                            />
                        </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

<script type="text/javascript">
   function updateRpInUserSetting(usersNeedToCheck, urlAPI, token) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
     const userConfirmed = confirm('Are you sure you want to update user settings?');
    if (!userConfirmed) {
        alert('Action canceled.');
        return;
    }

    $.ajax({
        url: urlAPI,
        method: "POST",
        headers: {
            'Authorization': 'Bearer ' + token,
            'X-CSRF-TOKEN': csrfToken
        },
        contentType: 'application/json',
        data: JSON.stringify({ users: usersNeedToCheck }),
        success: function(response) {
            alert('Reports updated successfully!');
            location.reload();
        },
        error: function(xhr) {
            location.reload();
            console.error('Error:', xhr.responseText);
        }
    });
}

</script>