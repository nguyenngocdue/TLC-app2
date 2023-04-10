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
            <x-renderer.item-render-props id={{$id}} :item="$item" :dataSource="$propsOfMainPage" status={{$status}} action={{$action}} type={{$type}} modelPath={{$modelPath}} />
        </div>
        @foreach($propsIntermediate as $key => $props)
            @php
            $propsOfIntermediatePage = App\Utils\Support\WorkflowFields::parseFields($props, $values, $defaultValues, $status, $type);
            @endphp
        <x-renderer.editable.modal-intermediate key={{$key}} action={{$action}} type={{$type}} status={{$status}} id={{$id}} modelPath={{$modelPath}} :actionButtons="$actionButtons" :props="$props" :item="$item" :dataSource="$propsOfIntermediatePage"  />
        @endforeach
        <div class="flex justify-end dark:bg-gray-800 px-5">
            <div class="my-5">
                @if($buttonSave)
                    @switch($action)
                        @case('edit')
                        <button type="submit" class="px-2.5 py-2  inline-block  font-medium text-sm leading-tight rounded focus:ring-0 transition duration-150 ease-in-out bg-blue-600 text-white shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none active:bg-blue-800 active:shadow-lg">
                            <i class="fa-solid fa-floppy-disk mr-2"></i>Save</button>
                            @break
                        @case('create')
                        <button type="submit" onclick="this.form.submit(); this.disabled=true; this.classList.add('disabled:opacity-40')" class="px-2.5 py-2  inline-block  font-medium text-sm leading-tight rounded focus:ring-0 transition duration-150 ease-in-out bg-blue-600 text-white shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none active:bg-blue-800 active:shadow-lg">
                            <i class="fa-solid fa-floppy-disk mr-2"></i>Create</button>
                            @break
                        @default
                            
                    @endswitch
                @endif
                @if($action !== 'create')
                    @foreach($actionButtons as $key => $button)
                    @php
                    $isCheck = !isset($propsIntermediate[$key]) || empty($propsIntermediate[$key]);
                    $isClosedAt = (isset($button['closed_at'])&& $button['closed_at'] == true);
                    @endphp
                    @if(isset($button['closed_at']) && $button['is_close'] == true)
                    <button type="button" title="Person can't close ,because you not owner document" class="px-2.5 py-2 inline-block disabled:opacity-40 font-medium text-sm leading-tight rounded focus:ring-0 transition duration-150 ease-in-out bg-purple-600 text-white shadow-md focus:outline-none"
                            disabled
                            >
                            Next <i class="fa-regular fa-arrow-right"></i> (to {{$button['label']}})
                        </button>
                    @else
                    <button {{$isCheck ? 'type=submit '. '@click=changeStatus("'.$key .'")' : 'type=button '. '@click=toggleIntermediate("'.$key .'")' }} 
                    class="px-2.5 py-2  inline-block  font-medium text-sm leading-tight rounded focus:ring-0 transition duration-150 ease-in-out bg-purple-600 text-white shadow-md hover:bg-purple-700 hover:shadow-lg focus:bg-purple-700 focus:shadow-lg focus:outline-none active:bg-purple-800 active:shadow-lg"
                            >
                            Next <i class="fa-regular fa-arrow-right"></i> (to {{$button['label']}})
                        </button>
                    @endif
                    @endforeach
                @endif
            </div>
        </div>
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
