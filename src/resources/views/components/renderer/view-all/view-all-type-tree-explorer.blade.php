<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>

<div class="grid grid-cols-12 gap-2">
    <div class="col-span-4 border rounded p-2 mt-2 overflow-x-auto">
        <div id="json_tree_1"></div>
    </div>
    <div class="col-span-8 border rounded p-2 mt-2 overflow-x-auto">
        <div id="tree_explorer_1"></div>
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
</script>

<script>
    const jsonTree = @json($tree);
    const owner_id = {{$ownerId}};
    $(function () { 
        $('#json_tree_1').jstree({ 
            'core' : {
                'data' : jsonTree,
                "check_callback" : true,
            },
            'plugins' : ["contextmenu"],
            'contextmenu': {
                'items': function(node) {
                    var tree = $("#json_tree_1").jstree(true);
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
                            "action": function (obj) {
                                tree.delete_node(node);
                                console.log("Deleting",node)
                            },
                            "_disabled": function(node) {
                                return node.parent === "#"; // Disable delete for root nodes    }
                            }
                        }
                    }
                }
            }
        });

        $('#json_tree_1').on("changed.jstree", function (e, data) {
            console.log(data.selected);
            if(Array.isArray(data.selected)){
                // console.log(data.selected);
                const treeBodyObjectId = data.selected[0]
                //No load if user click on the department
                if(!isNaN(treeBodyObjectId) && (!isNaN(parseFloat(treeBodyObjectId))) )loadRenderer(treeBodyObjectId)
                else console.log("Selected ID is Not a number:", treeBodyObjectId)
            }
        });

        $('#json_tree_1').on('rename_node.jstree', function(e, data) {
            var newName = data.text; // New name after renaming
            var nodeId = data.node.id; // Node ID
            console.log(data.node)
            console.log("Node renamed, ID: " + nodeId + ", New Name: " + newName);

            // Now send the new name to your server via AJAX
            //In case rename an existing node
            if(data.node?.data?.item_id) ajaxRenameItem(data.node.data.item_id, newName);
        });
    });
</script>