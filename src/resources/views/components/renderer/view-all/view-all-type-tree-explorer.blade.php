<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>

<div class="grid grid-cols-12 gap-2">
    <div class="col-span-4 border rounded p-2 mt-2 overflow-x-auto">
        <div id="json_tree_1" ></div>
    </div>
    <div class="col-span-8 border rounded p-2 mt-2 overflow-x-auto">
        <div id="tree_explorer_1" ></div>
    </div>
</div>

<script>
    function loadRenderer(disciplineId) {
        const url = "{{$route}}";
        $.ajax({
            url,
            data: {
                disciplineId,
            },
        }).then(res=>{
            var element = document.createElement('textarea');
            element.innerHTML = res.hits;
            $("#tree_explorer_1").html(element.textContent)
        })
    }
</script>

<script>
    const jsonTree = @json($tree);
    $(function () { 
        $('#json_tree_1').jstree({ 'core' : {
            'data' : jsonTree
        } });

        $('#json_tree_1').on("changed.jstree", function (e, data) {
            // console.log(data.selected);
            if(Array.isArray(data.selected)){
                // console.log(data.selected);
                const disciplineId = data.selected[0]
                loadRenderer(disciplineId)
            }
        });
    });
</script>