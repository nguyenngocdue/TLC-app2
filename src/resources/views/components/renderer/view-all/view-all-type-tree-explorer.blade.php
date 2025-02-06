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
    function ajaxCreateNewDoc(tree, node, owner_id){
        $.ajax({
            url: "{{$createNewDocShortRoute}}",
            method: "POST",
            data: {                
                parent_type: node.data.parent_type,
                parent_id: node.data.parent_id,
                name: "New Procedure",
                owner_id,
            },
            success: (res) => {
                const newNode = tree.create_node(node, {
                    id: res.hits.id,
                    data: {
                        my_id: res.hits.id,
                        my_type: "pp_doc",
                    },
                    text: "New Procedure #" + res.hits.id,
                    icon: "fa-regular fa-file text-blue-400"
                })
                tree.edit(newNode)
            },
            error: (jqXHR) => toastr.error(jqXHR.responseJSON.message),
        })
    }

    function ajaxRenameDoc(nodeId, newName){
        $.ajax({
            url: "{{$updateDocRoute}}",
            method: "POST",
            data: {
                id: nodeId,
                name: newName,
            },          
            error: (jqXHR) => toastr.error(jqXHR.responseJSON.message),
        })
    }

    function ajaxRenameFolder(nodeId, newName){
        $.ajax({
            url: "{{$updateFolderRoute}}",
            method: "POST",
            data: {
                id: nodeId,
                name: newName,
            },          
            error: (jqXHR) => toastr.error(jqXHR.responseJSON.message),
        })
    }

    function ajaxDeleteDoc(nodeId, owner_id){
        $.ajax({
            url: "{{$updateDocRoute}}",
            method: "POST",
            data: {
                id: nodeId,
                deleted_by: owner_id,
                deleted_at: new Date().toISOString(),
            },
            success: (res) => toastr.success("The Document has been deleted."),
            error: (jqXHR) => toastr.error(jqXHR.responseJSON.message),
        })
    }

    function ajaxDeleteFolder(nodeId, owner_id){
        $.ajax({
            url: "{{$updateFolderRoute}}",
            method: "POST",
            data: {
                id: nodeId,
                deleted_by: owner_id,
                deleted_at: new Date().toISOString(),
            },
            success: (res) => toastr.success("The Folder has been deleted."),
            error: (jqXHR) => toastr.error(jqXHR.responseJSON.message),
        })
    }
</script>

<script>
    const jsonTree = @json($tree);
    const owner_id = {{$ownerId}};
    const showSearch = {{$showSearch}} ? 1 : 0;
    $(function () { 
        const plugins = ["sort", "contextmenu", "wholerow",/* "dnd" */]
        if(showSearch) plugins.push("search")
        $('#json_tree_1').jstree({ 
            core : {
                data : jsonTree,                
                check_callback: function(operation, node, parent, position, more) {
                    // Control Draggable and Droppable in a unified way
                    if (operation === "move_node") {
                        console.log("Start to check...")
                        // Check if the node itself is draggable
                        if (node.data && node.data.draggable == false) return false; // Disable dragging for non-draggable nodes
                        // Check if the target parent is droppable
                        if (parent && parent.data && parent.data.droppable == false) return false; // Disable dropping on non-droppable nodes
                        if (parent.id === "#") return false; // Prevent dropping at the root level                    
                    }
                    console.log("OK",operation, node, parent, position, more);
                    return true; // Allow all other operations
                },
                // sort: function(a, b) {
                //     console.log(a, b);
                //     return this.get_text(a).toLowerCase() > this.get_text(b).toLowerCase() ? 1 : -1;
                // }
            },
            plugins,
            contextmenu: {
                items: function(node) {
                    var tree = $("#json_tree_1").jstree(true);
                    // const menu = {}
                    console.log(node);
                    switch( node.data.my_type)
                    {
                        case "department":  
                            return {
                                "Create": {
                                    "label": "Create New Procedure",
                                    "action": (obj) => ajaxCreateNewDoc(tree, node, owner_id),
                                },                                
                            }
                        break;
                        case "pp_folder":
                            return {
                                "Create": {
                                    "label": "Create New Procedure",
                                    "action": (obj) => ajaxCreateNewDoc(tree, node, owner_id),
                                },
                                "Rename": {
                                    "label": "Rename Folder",
                                    "action": (obj) => tree.edit(node),
                                },
                                "Delete": {
                                    "label": "Delete Folder",
                                    "action": (obj) => {
                                        // console.log("Delete", node);
                                        Swal.fire(actionConfirmObject([node.text], "DELETE"))
                                        .then((result) => {
                                            if (result.isConfirmed) tree.delete_node(node);
                                        });
                                    },
                                    "_disabled": function(node) {
                                        // Disable delete for root nodes
                                        return node.parent == "#"; 
                                    }
                                }
                            }
                        break;
                        case "pp_doc":
                            return {
                                // "Create": {
                                //     "label": "Create New Document",
                                //     "action": (obj) => ajaxCreateNewDoc(tree, node, owner_id),                            
                                // },
                                "Rename": {
                                    "label": "Rename",
                                    "action": (obj) => tree.edit(node),
                                },
                                "Delete": {
                                    "label": "Delete",
                                    "action": (obj) => {
                                        // console.log("Delete", node);
                                        Swal.fire(actionConfirmObject([node.text], "DELETE"))
                                        .then((result) => {
                                            if (result.isConfirmed) tree.delete_node(node);
                                        });
                                    },
                                    "_disabled": function(node) {
                                        // Disable delete for root nodes
                                        return node.parent == "#"; 
                                    }
                                }
                            }
                        break;
                        default:
                            return {
                                "Create": {
                                    "label": "No menu for [" + node.data.my_type + "]",
                                    "action": (obj) => ajaxCreateNewDoc(tree, node, owner_id),                            
                                },
                            }
                            break;
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
            var docId = data.node?.data?.my_id
            var myType = data.node?.data?.my_type
            // console.log("Renaming", newName, docId, myType);

            switch(myType){
                case "pp_doc":
                    if(docId) ajaxRenameDoc(docId, newName);
                    break;
                case "pp_folder":
                    if(docId) ajaxRenameFolder(docId, newName);
                    break;
                default:
                    console.log("Unknown how to Rename", myType);
                    break;
            }
        });

        $('#json_tree_1').on('move_node.jstree', function(e, data) {
            console.log("Moved", data);
        });

        $('#json_tree_1').on('delete_node.jstree', function(e, data) {
            const id = data.node?.data?.my_id
            var myType = data.node?.data?.my_type
            // console.log("Deleting",id);
            switch(myType){
                case "pp_doc":
                    if(id) ajaxDeleteDoc(id, owner_id);
                    break;
                case "pp_folder":
                    if(id) ajaxDeleteFolder(id, owner_id);
                    break;
                default:
                    console.log("Unknown how to Delete", myType);
                    break;
            }
        });
    });
</script>