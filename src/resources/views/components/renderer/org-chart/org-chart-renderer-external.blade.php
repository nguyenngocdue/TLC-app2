<div class="my-10">
    <div id="json_tree_1"></div>
</div>
<script>
    const jsonTree = @json($jsonTree);
    $(function () { 
        $('#json_tree_1').jstree({ 'core' : {
            'data' : jsonTree
        } });
    });
</script>