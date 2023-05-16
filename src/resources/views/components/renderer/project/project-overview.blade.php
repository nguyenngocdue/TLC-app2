@php $modalId = "modal-over-due-documents"; @endphp
<x-renderer.card title="{{$title}}" class="bg-white border" icon="fa-regular fa-bars-progress" >
    <x-renderer.table showNo=1 :columns="$columns" :dataSource="$dataSource" />
    
    <x-modals.modal-over-due-documents modalId="{{$modalId}}"></x-modals.modal-over-due-documents>
    @foreach($modalParams as $params)
        {{-- @dump($params) --}}
        @php $key = $params['docType']."_".$params['dueType']; @endphp
        <script>
            const {{$key}} = @json($params);
            $('#progress_'+'{{$key}}').click(()=>$('#btnHidden_'+'{{$key}}').click())
        </script>
        <x-renderer.button class="hidden" id="btnHidden_{{$key}}" keydown="closeModal('{{$modalId}}')" click="toggleModal('{{$modalId}}',{{$key}})">
            Open {{$key}}
        </x-renderer.button>
    @endforeach
</x-renderer.card>