

<div id="version-table"></div>

<script>
    function onFileSelected(){
        $('#aaabbbccc').on('change', function () {
            $("#divUpload").html("Uploading...");
            $("#tableVersion").html("");
            var formData = new FormData();
            formData.append('id', '{{$ppId}}');

            // Add all selected files to the FormData object
            $.each(this.files, function (index, file) {
                formData.append('attachment_procedure_policy[toBeUploaded][0][]', file);
            });

            // AJAX request to upload the files
            $.ajax({
                url: "{{$uploadFilePPRoute}}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    // Handle success response
                    // toastr.success(response.message);
                    loadDynamicPublishedVersion('{{$ppId}}');
                },
                error: function (xhr, status, error) {
                    console.log(xhr);
                    toastr.error(xhr.responseJSON);
                }
            });
        });
    }

    function onBtnDeleteClicked(versionId, fileName){
        // console.log("Delete button clicked", versionId, fileName)
        Swal.fire(actionConfirmObject([fileName], "DELETE")).then(
            (result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{$deleteFilePPRoute}}",
                        type: 'POST',
                        data: {
                            id: versionId
                        },
                        success: function (response) {
                            // Handle success response
                            // toastr.success(response.message);
                            loadDynamicPublishedVersion('{{$ppId}}');
                        },
                        error: function (xhr, status, error) {
                            console.log(xhr);
                            toastr.error(xhr.responseJSON);
                        }
                    });
                }
            }
        )
    }
</script>

<script>
     function loadPDF(pdfUrl, versionId) {
        $(".version-td").removeClass('bg-blue-300')
        console.log("hightling version id: ", versionId)
        $("#td-version-" + versionId).addClass('bg-blue-300')
        $('#pdfEmbed').attr('src', pdfUrl);
    }
</script>


<script>
    ppId = {{$ppId}}
    function renderVersionTable(hits, selectedVersionId) {
        const uploadBtn = `<div class="flex justify-center" id="divUpload">
            <label class="w-full justify-center px-2 py-1 mx-2 bg-blue-500 text-white rounded flex cursor-pointer" for="aaabbbccc"> <i class="mx-1 fa fa-upload"></i> Upload PDF files...</label>
            <input id="aaabbbccc" type="file" class="hidden" multiple />
        </div>`
        if(hits.length == 0) {
            $('#version-table').html(uploadBtn)
            onFileSelected()
            return
        }
        let trs = ""
        hits.forEach(hit=>{
            let tr = `<tr>`
            tr += `<td id="td-version-${hit['id']}" class="version-td p-1 cursor-pointer hover:border hover:border-blue-400 hover:border-dashed r1ounded">
                    <span onclick="loadPDF('${hit['src']}', ${hit['id']})">
                        ${hit['fileName']}
                        <br/>
                        <span class="flex gap-2">
                            <img class="w-6 h-6 rounded-full" src="${hit['avatar']}" />
                            ${hit['uploaded_by']}
                            (${hit['uploaded_at']})
                        </span>
                    </span>                                
                </td>`
            tr += `<td class="text-center">
                    <input name="published_version" 
                        type="radio"                        
                        class="p-1 m-2 cursor-pointer hover:underline" 
                        ${hit['id'] == selectedVersionId ? 'checked' : ''}
                        onclick="setPublishedVersion('${hit['id']}')"
                        /> 
                </td>`
            tr += `<td>
                    <button class="px-2 py-1 mx-2 text-red-500 bg-white hover:bg-red-500 hover:text-white rounded" 
                        onclick="onBtnDeleteClicked(${hit['id']}, '${hit['fileName']}')"
                        >
                        <i class="fa fa-trash"></i>
                    </button>
                </td>`
            tr += `</tr>`

            trs += tr
        })

        const table = `<table id="tableVersion" class="w-full">
            <tr>
                <th>File Name</th>
                <th>Published</th>
                <th></th>
            </tr>
            ${trs}
        </table>`
        $('#version-table').html(uploadBtn + table)

        //This has to be done after the table is rendered
        hits.forEach(hit=>{
            if(hit['id'] == selectedVersionId) loadPDF(hit['src'], hit['id']);
        })
        onFileSelected()
    }

    function loadDynamicPublishedVersion(id){
        $.ajax({
            url: '{{$loadDynamicPublishedVersion}}',
            type: 'GET',
            data: {
                ppId: id,
            },
            success: function(response) {
                renderVersionTable(response.hits, response.selectedVersionId);
            },
            error: function(xhr, status, error) {
                toastr.error(xhr.responseJSON);
            }
        });
   }

    function setPublishedVersion(versionId) {
        $.ajax({
            url: '{{$updatePPRoute}}',
            type: 'POST',
            data: {
                id: '{{$ppId}}',
                version_id: versionId
            },
            success: function(response) {
                console.log(response);
            }
        });
    }

    loadDynamicPublishedVersion(ppId);
</script>