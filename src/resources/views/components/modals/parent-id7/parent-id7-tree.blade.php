{{-- @dump($treeSource) --}}
<script>
jsonTree = @json($treeSource);
inputId = '{{$inputId}}';
</script>
<div id="json_tree_1" ></div>

<script src="{{asset('js/modals/'.$jsFileName)}}"></script>