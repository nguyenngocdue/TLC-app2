@foreach($notifyTo as $notifyToItem)
    <label class="cursor-pointer">
        <input type="radio" class="mx-1" name="notify_to" value="{{$notifyToItem->id}}" id="{{$notifyToItem->id}}"/>{{$notifyToItem->name}}
    </label>
    <br/>    
@endforeach

<div class="my-2 rounded border">
    <div class="flex border rounded">                
        <input id="txt-search-box-2" class="p-2 w-full" placeholder="Search Tree"/>
        <button disabled class="p-2 bg-blue-500 text-white rounded"><i class="fa fa-search"></i></button>
    </div>
    <div id="json_tree_2"></div>
</div>

<script>
    notifyToId = {{$notifyToId}};
    notifyToHodExcluded = @json($notifyToHodExcluded);
    notifyToMemberExcluded = @json($notifyToMemberExcluded);
    jsonTree2 = @json($notifyToTree);    
    function loadJsonOntoTree(notifyToId){
        var jsonTree2a = JSON.parse(JSON.stringify(jsonTree2));

        console.log("notifyToId", notifyToId, notifyToHodExcluded, notifyToMemberExcluded);

        $('#json_tree_2').jstree({ 
            core : {
                data : jsonTree2a,
            },
            plugins: ['wholerow', 'checkbox', 'search'],
        });
    
        $('#json_tree_2').on('ready.jstree', function() {
            $('#json_tree_2').jstree('check_all');
            notifyToHodExcluded.forEach(function(id) {
                $('#json_tree_2').jstree('uncheck_node', "hod_"+id);
            });
            notifyToMemberExcluded.forEach(function(id) {
                $('#json_tree_2').jstree('uncheck_node', "member_"+id);
            });
        });

        $('#json_tree_2').on('changed.jstree', function (e, selectedNode) {    
            // console.log("selectedNode", selectedNode);
            // for (let i = 0; i < selectedNode.selected.length; i++) {
            //     const node = selectedNode.instance.get_node(selectedNode.selected[i])
            // }
            console.log("Selected nodes:", selectedNode.selected); // This still gives selected nodes
    
            const allNodes = $('#json_tree_2').jstree(true).get_json('#', { 'flat': true });
            const uncheckedNodes = allNodes
                .filter(node => !selectedNode.selected.includes(node.id))
                .filter(node => node.id.startsWith('member_') || node.id.startsWith('hod_'))

            console.log("Unchecked nodes:", uncheckedNodes.map(node => node.id));
        })  
    }
    loadJsonOntoTree(notifyToId);

    var to = false;
    $('#txt-search-box-2').keyup(function () {
        if(to) { clearTimeout(to); }
        to = setTimeout(function () {
            var v = $('#txt-search-box-2').val();
            $('#json_tree_2').jstree(true).search(v);
        }, 250);
    });
    
     
</script>