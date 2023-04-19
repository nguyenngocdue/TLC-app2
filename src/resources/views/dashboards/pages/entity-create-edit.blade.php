@extends('layouts.app')

@php
$user = auth()->user();
$editType = Str::plural($type);
$id = $action === "edit" ? $values->id : "";
$status = $status ?? $values->status ?? null;
$ownerId = $values->owner_id ?? null;
$allProps = $superProps['props'];
[$status, $statuses, $props, $actionButtons, $transitions, $buttonSave,$propsIntermediate] = App\Utils\Support\WorkflowFields::resolveSuperProps($superProps ,$status,$type,$isCheckColumnStatus,$ownerId);
$propsOfMainPage = App\Utils\Support\WorkflowFields::parseFields($props, $values, $defaultValues,$status,$type);
@endphp
@section('topTitle', $topTitle)
@section('title', $title )
@section('status', $action === "edit" ? $status : null)
@section('content')
    <script>
        k = @json($listenerDataSource);

        listenersOfDropdown2 = @json($listeners2);
        filtersOfDropdown2 = @json($filters2);

        listenersOfDropdown4s = @json($listeners4);
        filtersOfDropdown4s = @json($filters4);
    </script>
    <div class="px-4">
        <x-controls.workflow403-checker action="{{$action}}" type="{{$type}}" status="{{$status}}" />
        <x-controls.header-alert-validation :strProps="$allProps" />
        <x-renderer.test-status-and-accessible type={{$type}} renderId={{$id}} status={{$status}} action={{$action}} :dryRunToken="$dryRunToken" :statuses="$statuses" />
        <x-controls.status-visibility-checker :propsOfMainPage="$propsOfMainPage" :allProps="$allProps"/>
        <form class="w-full mb-8 bg-white rounded-lg  dark:bg-gray-800 mt-2" id="form-upload" method="POST" enctype="multipart/form-data" action="{{ route($action === "create" ? $editType.'.store': $editType.'.update', $action === "create" ? '' : $id )}} ">
            @csrf
            <input name="tableNames[table00]" value="(the_form)" type='hidden' /> {{-- This line is required for updating  --}}
            <div class=" grid grid-cols-12 px-4">
                @method($action === "create" ? 'POST' : 'PUT')
                @if($type != 'qaqc_insp_chklst_shts')
                    <x-renderer.item-render-props id={{$id}} :item="$item" :dataSource="$propsOfMainPage" status={{$status}} action={{$action}} type={{$type}} modelPath={{$modelPath}} />
                @else
                <x-controls.insp-chklst.item-render-check-sheet id={{$id}} :item="$item" :type="$type"/>
                    {{-- <x-renderer.item-render-props id={{$id}} :item="$item" :dataSource="$propsOfMainPage" status={{$status}} action={{$action}} type={{$type}} modelPath={{$modelPath}} /> --}}
                @endif
            </div>
            @foreach($propsIntermediate as $key => $props)
                @php $propsOfIntermediatePage = App\Utils\Support\WorkflowFields::parseFields($props, $values, $defaultValues, $status, $type); @endphp
                <x-renderer.editable.modal-intermediate key={{$key}} action={{$action}} type={{$type}} status={{$status}} id={{$id}} modelPath={{$modelPath}} :actionButtons="$actionButtons" :props="$props" :item="$item" :dataSource="$propsOfIntermediatePage"  />
            @endforeach
            <x-controls.action-buttons :buttonSave="$buttonSave" :action="$action" :actionButtons="$actionButtons" :propsIntermediate="$propsIntermediate"/>
        </form>
    </div>
    <x-renderer.editable.modal-broadcast-notification />
    @if($action == 'edit')
    <div class="px-4">
        <div class="w-full p-2 bg-white rounded-lg  dark:bg-gray-800">
            <x-renderer.card title="Timeline">
                <x-controls.time-line2 id={{$id}} modelPath={{$modelPath}} :props="$props" />
            </x-renderer.card>
        </div>
    </div>
    @endif
    <script type="text/javascript">
        userCurrent = @json($user);
        window.Echo.channel('edit.'+'{{$type}}' +'-'+ '{{$id}}')
            .listen('.BroadcastUpdateEvent.' + '{{$type}}' + '-'+ '{{$id}}', (data) => {
                const user = data['user']
                const dataSource = data['dataSource']
                if(userCurrent['id'] !== user['id']){
                    $(".buttonToggleNotification").click()
                    setTimeout(() => {
                        $(".userNameNotification").text(`${user['name']}`)
                    }, 5);
                }
            });
    </script>
@endsection
