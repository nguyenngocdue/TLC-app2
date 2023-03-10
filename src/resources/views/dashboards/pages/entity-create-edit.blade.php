@extends('layouts.app')

@php
$editType = Str::plural($type);
$id = $action === "edit" ? $values->id : "";
$status = $status ?? $values->status ?? null;
[$status, $statuses, $props, $actionButtons, $transitions, $buttonSave,$propsIntermediate] = App\Utils\Support\WorkflowFields::resolveSuperProps($superProps ,$status,$type,$isCheckColumnStatus);
$result = App\Utils\Support\WorkflowFields::parseFields($props, $values, $defaultValues,$status,$type);
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
    <x-controls.header-alert-validation :strProps="$props" />
    <x-renderer.test-status-and-accessible type={{$type}} renderId={{$id}} status={{$status}} action={{$action}} :dryRunToken="$dryRunToken" :statuses="$statuses" />
    <form class="w-full mb-8 bg-white rounded-lg  dark:bg-gray-800" id="form-upload" method="POST" enctype="multipart/form-data" action="{{ route($action === "create" ? $editType.'.store': $editType.'.update', $action === "create" ? '' : $id )}} ">
        @csrf
        <input name="tableNames[table00]" value="(the_form)" type='hidden' /> {{-- This line is required for updating  --}}
        <div class=" grid grid-cols-12 px-4">
            @method($action === "create" ? 'POST' : 'PUT')
            <x-renderer.item-render-props id={{$id}} :dataSource="$result" status={{$status}} action={{$action}} type={{$type}} modelPath={{$modelPath}} />
        </div>
        @foreach($propsIntermediate as $key => $props)
        @php
        $resultIntermediate = App\Utils\Support\WorkflowFields::parseFields($action, $props, $values, $defaultValues, $status, $type);
        @endphp
        <template x-if="isIntermediateOpen['{{$key}}']">
            <div tabindex="-1" class="fixed sm:p-0 md:p-0 top-0 left-0 right-0 z-50 lg:p-4 h-full bg-gray-100 dark:bg-slate-400 dark:bg-opacity-70 bg-opacity-70 justify-center items-center flex" aria-hidden="true" @keydown.escape="closeIntermediate('{{$key}}')">
                <div class="relative sm:mx-0 md:mx-5  w-full lg:mx-10 xl:mx-16 2xl:mx-20 h-auto md:h-auto sm:h-auto" @click.away="closeIntermediate('{{$key}}')">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <!-- Modal header -->
                        <div class="items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                            <div class="flex">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Intermediate
                                </h3>
                                <button type="button" @click="closeIntermediate('{{$key}}')" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="large-modal">
                                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>
                        </div>
                        <!-- Modal body -->
                        <div class='overflow-y-scroll p-4'>
                            <div class="grid grid-cols-12 px-4">
                                <x-controls.workflow403-checker action="{{$action}}" type="{{$type}}" status="{{$status}}" />
                                <x-controls.header-alert-validation :strProps="$props" />
                                <x-renderer.item-render-props id="{{$id}}" :dataSource="$resultIntermediate" action={{$action}} type={{$type}} modelPath={{$modelPath}} />
                            </div>
                        </div>
                        <div class="flex items-center justify-end rounded-b border-t border-solid border-slate-200 dark:border-gray-600 p-2">
                            <button type="submit" class="mr-3 px-2.5 py-2  inline-block  font-medium text-sm leading-tight rounded focus:ring-0 transition duration-150 ease-in-out bg-purple-600 text-white shadow-md hover:bg-purple-700 hover:shadow-lg focus:bg-purple-700 focus:shadow-lg focus:outline-none active:bg-purple-800 active:shadow-lg">
                                Next -> (to {{$actionButtons[$key]['label']}})
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        @endforeach
        <div class="flex justify-end dark:bg-gray-800 px-5">
            <div class="my-5">
                @if($buttonSave)
                <button type="submit" class="px-2.5 py-2  inline-block  font-medium text-sm leading-tight rounded focus:ring-0 transition duration-150 ease-in-out bg-blue-600 text-white shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none active:bg-blue-800 active:shadow-lg">
                    <i class="fa-solid fa-floppy-disk mr-2"></i>Save</button>
                @endif
                @foreach($actionButtons as $key => $button)
                @php
                $isCheck = !isset($propsIntermediate[$key]) || empty($propsIntermediate[$key]);
                @endphp
                <button {{$isCheck ? 'type=submit '. '@click=changeStatus("'.$key .'")' : 'type=button '. '@click=toggleIntermediate("'.$key .'")' }} class="px-2.5 py-2  inline-block  font-medium text-sm leading-tight rounded focus:ring-0 transition duration-150 ease-in-out bg-purple-600 text-white shadow-md hover:bg-purple-700 hover:shadow-lg focus:bg-purple-700 focus:shadow-lg focus:outline-none active:bg-purple-800 active:shadow-lg">
                    Next <i class="fa-regular fa-arrow-right"></i> (to {{$button['label']}})
                </button>
                @endforeach
            </div>
        </div>
    </form>
</div>
@endsection
