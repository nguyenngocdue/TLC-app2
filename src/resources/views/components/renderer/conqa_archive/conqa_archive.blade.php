<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>

<div class="grid grid-cols-12 gap-2">
    <div class="col-span-4 border no-print">
        BTH
        <div id="json_tree_1" class="overflow-x-hidden" ></div>
    </div>
    <div class="col-span-12 lg:col-span-8 border">
        <div id="checklist_1" class="overflow-x-hidden" ></div>
    </div>
</div>

<script>
    const jsonTree = @json($tree);
    function loadChecklistRenderer(id) {
        const url = "{{$route}}";
        $.ajax({
            url,
            data: {
                id,
                projName: "{{$projName}}",
            },
        }).then(res=>{
            // console.log(res)
            var element = document.createElement('textarea');
            element.innerHTML = res.hits;
            $("#checklist_1").html(element.textContent)
        })
    }

    $(function () { 
        $('#json_tree_1').jstree({ 'core' : {
            'data' : jsonTree
        } });

        $('#json_tree_1').on("changed.jstree", function (e, data) {
            // console.log(data.selected);
            if(Array.isArray(data.selected)){
                const id = data.selected[0]
                for(let i =0; i<jsonTree.length;i++){
                    if(jsonTree[i]['id'] == id){
                        const node = jsonTree[i]
                        if(node.type == 'checklist'){
                            $("#checklist_1").html("Loading...")
                            loadChecklistRenderer(id)
                        }else {
                            $("#checklist_1").html("")
                        }
                    }
                }
            }
        });
    });
</script>