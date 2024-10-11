<div class="my-10">
    <div id="json_tree_2"></div>
</div>
<script>
    jsonTree2 = @json($notifyTo);    
    $('#json_tree_2').jstree({ 
        core : {
            data : jsonTree2,
        },
        plugins: ['wholerow', 'checkbox'],
    });
    
</script>