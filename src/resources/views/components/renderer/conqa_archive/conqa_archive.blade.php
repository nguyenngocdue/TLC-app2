<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>

<div class="grid grid-cols-12 gap-2">
    <div class="col-span-4 border no-print">
        <div>
            {{$folderName}}
            <div id="json_tree_1" class="overflow-x-hidden" ></div>
        </div>
        <div class="border bg-gray-200 text-center p-4 mt-4"><span id="folderUuid"></span></div>
    </div>
    <div class="col-span-12 lg:col-span-8 border">
        <div id="checklist_1" class="overflow-x-hidden" ></div>
    </div>
</div>

<script>
    function loadChecklistRenderer(folderUuid) {
        const url = "{{$route}}";
        $.ajax({
            url,
            data: {
                folderUuid,
                projName: "{{$projName}}",
            },
        }).then(res=>{
            // console.log(res)
            var element = document.createElement('textarea');
            element.innerHTML = res.hits;
            $("#checklist_1").html(element.textContent)
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
                const folderUuid = data.selected[0]
                for(let i =0; i<jsonTree.length;i++){
                    if(jsonTree[i]['id'] == folderUuid){
                        const node = jsonTree[i]
                        const a = document.createElement('a');
                        if(node.type == 'checklist'){
                            $("#checklist_1").html("<div class='w-full m-4 p-4 text-blue-600'>Loading Checklist...</div>")
                            loadChecklistRenderer(folderUuid)
                            a.href = "/conqa_archive/checklist/{{$projName}}/" + node.id;
                            a.textContent = "checklist/{{$projName}}/" + node.id;
                            $("#folderUuid").html(a)
                        }else {
                            $("#checklist_1").html("")
                            a.href = "/conqa_archive/folder/{{$projName}}/" + folderUuid;
                            a.textContent = "folder/{{$projName}}/" + folderUuid;
                            $("#folderUuid").html(a)
                        }
                    }
                }
            }
        });
    });
</script>