<div class="flex" id="divUpload">
    <label class="px-2 mx-2 bg-blue-500 text-white rounded flex" for="aaabbb"> <i class="fa fa-upload"></i> Upload</label>
    <input id="aaabbb" type="file" multiple />
</div>

<script>
    $('#aaabbb').on('change', function () {
        $("#divUpload").html("Uploading...");
        var formData = new FormData();
        formData.append('id', '{{$ppId}}');

        // Add all selected files to the FormData object
        $.each(this.files, function (index, file) {
            formData.append('attachment_procedure_policy[toBeUploaded][0][]', file);
        });

        // AJAX request to upload the files
        $.ajax({
            url: "{{$uploadPPRoute}}",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                // Handle success response
                toastr.success(response.message);
            },
            error: function (xhr, status, error) {
                console.log(xhr);
                toastr.error(xhr.responseJSON);
            }
        });
    });
</script>

<script>
     function loadPDF(pdfUrl, versionId) {
        $(".version-td").removeClass('bg-blue-300')
        $("#td-version-" + versionId).addClass('bg-blue-300')
        $('#pdfEmbed').attr('src', pdfUrl);
    }
</script>

@if($versions->count())
    <table class="w-full">
        <tr>
            <th>File Name</th>
            <th>Published</th>
            <th></th>
        </tr>
        @foreach($versions as $version)
            <tr>
                <td id="td-version-{{$version['id']}}" class="version-td p-1 cursor-pointer hover:border hover:border-blue-400 hover:border-dashed r1ounded">
                    <span class="" onclick="loadPDF('{!! $version['src'] !!}', {{$version['id']}})">
                        {{ $version['fileName'] }}
                        <br/>
                        <span class="flex gap-2">
                            <img class="w-6 h-6 rounded-full" src="{{$version['avatar']}}" />
                            {{$version['uploaded_by']}}
                            ({{$version['uploaded_at']}})
                        </span>
                    </span>                                
                </td>
                <td class="text-center">
                    <input name="published_version" 
                        type="radio"
                        @checked($versionId == $version['id'])
                        class="p-1 m-2 cursor-pointer hover:underline" 
                        onclick="setPublishedVersion('{{$version['id']}}')"                        
                        /> 
                </td>
                <td>
                    <button class="px-2 mx-2 bg-red-500 text-white rounded"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
        @if($versionId == $version['id'])
            <script>
                loadPDF('{!! $version['src'] !!}', '{{$version['id']}}');
            </script>
        @endif
        @endforeach            
    </table>
@endif

<script>
   

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
</script>