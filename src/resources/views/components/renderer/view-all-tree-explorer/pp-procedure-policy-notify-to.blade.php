<label class="cursor-pointer"><input type="radio" class="mx-1" name="notify_to" />Everyone</label><br/>
<label class="cursor-pointer"><input type="radio" class="mx-1" name="notify_to" />All Head of Departments</label><br/>
<label class="cursor-pointer"><input type="radio" class="mx-1" name="notify_to" />All Team Members</label><br/>

<div class="my-2 rounded border">
    <div class="flex border rounded">                
        <input id="txt-search-box-2" class="p-2 w-full" placeholder="Search Tree"/>
        <button disabled class="p-2 bg-blue-500 text-white rounded"><i class="fa fa-search"></i></button>
    </div>
    <div id="json_tree_2"></div>
</div>

<script>
    jsonTree2 = @json($notifyTo);    
    $('#json_tree_2').jstree({ 
        core : {
            data : jsonTree2,
        },
        plugins: ['wholerow', 'checkbox', 'search'],
    });

    $('#json_tree_2').on('ready.jstree', function() {
        $('#json_tree_2').jstree('check_all');
    });

    var to = false;
    $('#txt-search-box-2').keyup(function () {
        if(to) { clearTimeout(to); }
        to = setTimeout(function () {
            var v = $('#txt-search-box-2').val();
            $('#json_tree_2').jstree(true).search(v);
        }, 250);
    });
    
</script>