@extends('layouts.app')
@php
$user = auth()->user();
$editType = Str::plural($type);
$id = ($action === "edit") ? $values->id : "";
$status = $status ?? $values->status ?? null;
$ownerId = $values->owner_id ?? null;
$allProps = $superProps['props'];
$tmp = App\Utils\Support\WorkflowFields::resolveSuperProps($superProps ,$status,$type,$hasStatusColumn,$ownerId);
[$status, $statuses, $props, $actionButtons, $transitions, $buttonSave,$propsIntermediate] = $tmp;
$propsOfMainPage = App\Utils\Support\WorkflowFields::parseFields($props, $values, $defaultValues,$status,$type);
$allowed = App\Utils\Support\Json\SuperWorkflows::isAllowed($status, $type);

$formWidth = "md:w-3/4";
if(in_array($type,["qaqc_insp_chklst_shts","hse_insp_chklst_shts"])){
$formWidth = "md:w-1/2";
}
@endphp

@section('topTitle', $topTitle)
@section('title', $title )
@section('status', $action === "edit" ? $status : null)
@section('docId', $docId)
@section('content')
<script>
    k = @json($listenerDataSource);
    ki = makeKi(k)

    listenersOfDropdown2 = @json($listeners2);
    filtersOfDropdown2 = @json($filters2);

    listenersOfDropdown4s = @json($listeners4);
    filtersOfDropdown4s = @json($filters4);
</script>
<div class="px-4 pt-2 bg-body">
    <x-elapse />
    <x-controls.workflow403-checker allowed="{{$allowed}}" status="{{$status}}"/>
    <x-controls.header-alert-validation :strProps="$allProps" />
    <x-renderer.test-status-and-accessible :item="$item" type={{$type}} renderId={{$id}} status={{$status}} action={{$action}} :dryRunToken="$dryRunToken" :statuses="$statuses" />
    <x-controls.status-visibility-checker :propsOfMainPage="$propsOfMainPage" :allProps="$allProps" />
    <x-elapse />

    @switch($type) 
        @case ('qaqc_insp_chklst_shts')  
            <x-renderer.chklst_header.qaqc_insp_chklst_shts  formWidth="{{$formWidth}}" :item="$item"/>
        @break
        @case ('hse_insp_chklst_shts')  
            <x-renderer.chklst_header.hse_insp_chklst_shts  formWidth="{{$formWidth}}" :item="$item"/>
        @break
    @endswitch
    
    <form class="w-full mt-2" id="form-upload" method="POST" enctype="multipart/form-data" action="{{ route($action === "create" ? $editType.'.store': $editType.'.update', $action === "create" ? '' : $id )}} ">
        @csrf
        <input name="tableNames[table00]" value="(the_form)" type='hidden' /> {{-- This line is required for updating  --}}
        <input name="redirect_back_to_last_page" value="{{$redirect}}" type='hidden' />  {{-- This line is required for profile and me --}}
        @method($action === "create" ? 'POST' : 'PUT')
        @switch($app['edit_renderer'])
        
            @case ('')
            <div class="px-2 flex justify-center">
                {{-- Table of content --}}
                <div class="fixed left-0">
                    <div class="text-center">
                        <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800" type="button" data-drawer-target="drawer-left" data-drawer-show="drawer-left" aria-controls="drawer-contact" data-drawer-body-scrolling="true" data-drawer-backdrop="false">
                            <i class="fa-solid fa-bars"></i>
                        </button>
                    </div>
                </div>
                <x-renderer.item-render-props width='{{$formWidth}}' id={{$id}} :item="$item" :dataSource="$propsOfMainPage" status={{$status}} action={{$action}} type={{$type}} modelPath={{$modelPath}} hasReadOnly={{$hasReadOnly}} />
                @if(!$hasReadOnly)
                    <div class="fixed right-0">
                        <x-controls.action-buttons isFloatingOnRightSide="true" :buttonSave="$buttonSave" :action="$action" :actionButtons="$actionButtons" :propsIntermediate="$propsIntermediate" type={{$type}} />
                    </div>
                @endif
            </div>
            @break        

            {{-- @case ('exam-renderer')
                <x-controls.exam-sheet.item-renderer-exam-sheet id={{$id}} :item="$item"/>
            @break --}}

            @case ('conqa-renderer')
                <x-renderer.conqa_archive.conqa_archive :item="$item"/>
            @break

            @default
            <x-feedback.alert type="error" message="Unknown how to render [{{$app['edit_renderer']}}] in entity-create-edit." />
            @break

        @endswitch
        <x-elapse />
        @foreach($propsIntermediate as $key => $props)
        @php $propsOfIntermediatePage = App\Utils\Support\WorkflowFields::parseFields($props, $values, $defaultValues, $status, $type); @endphp
        <x-renderer.editable.modal-intermediate key={{$key}} action={{$action}} type={{$type}} status={{$status}} id={{$id}} modelPath={{$modelPath}} :actionButtons="$actionButtons" :props="$props" :item="$item" :dataSource="$propsOfIntermediatePage" />
        @endforeach
        @if(!$hasReadOnly)
        <div class="px-2 flex justify-center">
            <div class="bg-white rounded-lg mt-2 w-full md:w-3/4">
                <x-controls.action-buttons :buttonSave="$buttonSave" :action="$action" :actionButtons="$actionButtons" :propsIntermediate="$propsIntermediate" type={{$type}} />
            </div>
        </div>
        @endif
        <script>
            listenerSubmitForm('form-upload');
        </script>
    </form>
</div>
<x-renderer.editable.modal-broadcast-notification />
@if($action == 'edit')
    @if(!in_array($type, ['conqa_archives']))
        <div class="px-4 flex justify-center bg-body">
            <div class="w-full md:w-3/4 p-2 rounded-lg  dark:bg-gray-800">
                <x-renderer.card title="Timeline">
                    <x-controls.time-line2 id={{$id}} modelPath={{$modelPath}} :props="$props" />
                </x-renderer.card>
            </div>
        </div>
    @endif
@endif
<x-homepage.left-drawer title="Table of Content">
    <x-homepage.table-of-content :item="$item" type="{{$type}}" />
</x-homepage.left-drawer>
<x-renderer.image-gallery :dataSource="$propsOfMainPage" action={{$action}} mode='create-edit'/>
<script type="text/javascript">
        userCurrent = @json($user);
        window.Echo.channel('edit.'+'{{$type}}' +'-'+ '{{$id}}')
        .listen('BroadcastUpdateEvent.' + '{{$type}}' + '-'+ '{{$id}}', (data) => {
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