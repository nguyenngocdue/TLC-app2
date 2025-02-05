<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>

<div class="grid grid-cols-12 gap-2">
    <div class="col-span-3 border rounded p-2 mt-2 overflow-x-auto bg-gray-100">
        @if($showSearch)
            <div class="flex border rounded">                
                <input id="txt-search-box" class="p-2 w-full" placeholder="Search Tree"/>
                <button disabled class="p-2 bg-blue-500 text-white rounded"><i class="fa fa-search"></i></button>
            </div>
        @endif
        <div id="json_tree_1"></div>
    </div>
    <div class="col-span-9 border rounded p-2 mt-2 overflow-x-auto bg-gray-100" >
        <div id="tree_explorer_1" style="height: 100%;"></div>
    </div>
</div>

<script>
    function loadRenderer(treeBodyObjectId) {
        const url = "{{$route}}";
        $.ajax({
            url,
            data: {
                treeBodyObjectId,
            },
        }).then(res=>{
            var element = document.createElement('textarea');
            element.innerHTML = res.hits;
            $("#tree_explorer_1").html(element.textContent)
        })
    }
</script>

<script>
    function ajaxCreateNewItem(tree, node, owner_id){
        $.ajax({
            url: "{{$createNewShortRoute}}",
            method: "POST",
            data: {
                department_id: node.data.item_id,
                name: "New Procedure",
                owner_id,
            },
            success: (res) => {
                const newNode = tree.create_node(node, {
                    data: {item_id: res.hits.id},
                    text: "New Procedure",
                })
                tree.edit(newNode)
            },
            error: (jqXHR) => toastr.error(jqXHR.responseJSON.message),
        })
    }

    function ajaxRenameItem(nodeId, newName){
        $.ajax({
            url: "{{$updateRoute}}",
            method: "POST",
            data: {
                id: nodeId,
                name: newName,
            },
            error: (jqXHR) => toastr.error(jqXHR.responseJSON.message),
        })
    }

    function ajaxDeleteItem(nodeId, owner_id){
        $.ajax({
            url: "{{$updateRoute}}",
            method: "POST",
            data: {
                id: nodeId,
                deleted_by: owner_id,
                deleted_at: new Date().toISOString(),
            },
            error: (jqXHR) => toastr.error(jqXHR.responseJSON.message),
        })
    }
</script>

<script>
    const jsonTree = @json($tree);
    const owner_id = {{$ownerId}};
    const showSearch = {{$showSearch}} ? 1 : 0;
    $(function () { 
        const plugins = ["contextmenu", "wholerow",/* "dnd" */]
        if(showSearch) plugins.push("search")
        $('#json_tree_1').jstree({ 
            core : {
                data : jsonTree,                
                check_callback: function(operation, node, parent, position, more) {
                    // Control Draggable and Droppable in a unified way
                    if (operation === "move_node") {
                        // Check if the node itself is draggable
                        if (node.data && node.data.draggable == false) return false; // Disable dragging for non-draggable nodes
                        // Check if the target parent is droppable
                        if (parent && parent.data && parent.data.droppable == false) return false; // Disable dropping on non-droppable nodes
                        if (parent.id === "#") return false; // Prevent dropping at the root level                    
                    }
                    return true; // Allow all other operations
                },
            },
            plugins,
            contextmenu: {
                items: function(node) {
                    var tree = $("#json_tree_1").jstree(true);
                    // const menu = {}
                    
                    return {
                        "Create": {
                            "label": "Create New",
                            "action": (obj) => ajaxCreateNewItem(tree, node, owner_id),                            
                        },
                        "Rename": {
                            "label": "Rename",
                            "action": (obj) => tree.edit(node),
                        },
                        "Delete": {
                            "label": "Delete",
                            "action": (obj) => tree.delete_node(node),                            
                            "_disabled": function(node) {
                                // Disable delete for root nodes
                                return node.parent == "#"; 
                            }
                        }
                    }
                }
            }
        });

        if(showSearch){
            var to = false;
            $('#txt-search-box').keyup(function () {
                if(to) { clearTimeout(to); }
                to = setTimeout(function () {
                    var v = $('#txt-search-box').val();
                    $('#json_tree_1').jstree(true).search(v);
                }, 250);
            });
        }

        $('#json_tree_1').on("changed.jstree", function (e, data) {
            if(Array.isArray(data.selected)){
                // console.log(data.selected);
                const treeBodyObjectId = data.selected[0]
                //Dispatch API call if the selected node is a number
                // loadRenderer(1)
                if(!isNaN(treeBodyObjectId) && (!isNaN(parseFloat(treeBodyObjectId))) )
                    loadRenderer(treeBodyObjectId)
                else 
                    console.log("Selected ID is Not a number:", treeBodyObjectId)
            }
        });

        $('#json_tree_1').on('rename_node.jstree', function(e, data) {
            var newName = data.text; // New name after renaming
            if(data.node?.data?.item_id) ajaxRenameItem(data.node.data.item_id, newName);
        });

        $('#json_tree_1').on('move_node.jstree', function(e, data) {
            console.log("Moved", data);
        });

        $('#json_tree_1').on('delete_node.jstree', function(e, data) {
            const id = data.node?.data?.item_id
            console.log("deleting",id);
            if(id) ajaxDeleteItem(id, owner_id);
            console.log("Deleted", id);
        });
    });
</script>