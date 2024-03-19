{{-- @php $modalId = "modal-over-due-documents"; @endphp --}}
<x-renderer.card title="{{$title}}" tooltip="ProjectOverview" class="bg-white p-4 border col-span-12 lg:col-span-6" icon="fa-regular fa-bars-progress" >
    <x-renderer.table showNo=1 :columns="$columns" :dataSource="$dataSource" />
    
    <x-modals.modal-over-due-documents modalId="{{$modalId}}"></x-modals.modal-over-due-documents>
    @foreach($modalParams as $params)
        @php $key = $params['docType']."_".$params['dueType']; @endphp
        <script> const {{$key}} = @json($params); </script>
    @endforeach
    {{-- @foreach($modalParams as $params)
        @php $key = $params['docType']."_".$params['dueType']; @endphp
        <script>
            const {{$key}} = @json($params);
            $('#progress_'+'{{$key}}')
                .click(()=>$('#btnHidden_'+'{{$key}}').click())
                .key
        </script>
        <x-renderer.button class="hidden1" id="btnHidden_{{$key}}" 
            keydownEscape="closeModal('{{$modalId}}')" 
            click="toggleModal('{{$modalId}}',{{$key}})"
            >
            Open {{$key}}
        </x-renderer.button>
    @endforeach --}}
</x-renderer.card>