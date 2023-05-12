@php $modalId = "modal-over-due-documents"; @endphp
<x-renderer.card title="Project Milestones" class="bg-white border" icon="fa-sharp fa-regular fa-users" >
    <x-renderer.table showNo=1 :columns="$columns" :dataSource="$dataSource" />
    
    <x-modals.modal-over-due-documents modalId="{{$modalId}}"></x-modals.modal-over-due-documents>
    @foreach($modalParams as $params)
        {{-- @dump($params) --}}
        <script>
            const {{$params['docType']."_".$params['dueType']}} = @json($params);
            // console.log({{$params['docType']."_".$params['dueType']}})
        </script>
        <x-renderer.button keydown="closeModal('{{$modalId}}')" click="toggleModal('{{$modalId}}',{{$params['docType'].'_'.$params['dueType']}})">
            Open {{$params['docType']."_".$params['dueType']}}
        </x-renderer.button>
    @endforeach
    
    <x-modals.modal-empty modalId="{{$modalId}}E"></x-modals.modal-empty>
    <x-renderer.button keydown="closeModal('{{$modalId}}E')"  click="toggleModal('{{$modalId}}E')">Open E</x-renderer.button>
</x-renderer.card>

<script>
</script>