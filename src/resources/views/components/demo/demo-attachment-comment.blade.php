<div class="grid gap-6 mb-8 md:grid-cols-2 ">
    <x-renderer.card title="Attachments">
        <form action="" method="GET">
            @dump($_GET['attachment_1'] ??[])
            <x-renderer.card title="Default attachment without attributes">
                {{-- @dd($attachmentData2) --}}
                <x-renderer.attachment2 name='attachment_1' :value="$attachmentData2['attachment_1']?? []" />
            </x-renderer.card>
            <br />
            @dump($_GET['attachment_2'] ??[])
            <x-renderer.card title="readonly ={ { true } }, destroyable ={ { true } }, showToBeDeleted  ={ { true } }">
                <x-renderer.attachment2 name='attachment_2' :value="$attachmentData2['attachment_2']?? []" readonly={{true}} showToBeDeleted={{true}} />
            </x-renderer.card>
            <br />
            @dump($_GET['attachment_3'] ??[])
            <x-renderer.card title="readonly = { { false} }, destroyable = { { false} }, showToBeDeleted = { { false } }">
                <x-renderer.attachment2 name='attachment_3' :value="$attachmentData2['attachment_3']?? []" destroyable={{false}} categoryName="attachment_2" showToBeDeleted={{false}} />
            </x-renderer.card>
            <br />
            <x-renderer.button htmlType='submit' type='primary'>Update</x-renderer.button>
        </form>
    </x-renderer.card>


    <x-renderer.card title="Comments">
        <form action="" method="GET">
            @dump($_GET['comment01'] ??[])
            <x-renderer.card title="Comment 01">
                allowed_to_delete=1
                <x-renderer.comment2 comment01Name="comment01" rowIndex=0 category=6 commentId=10 
                    commentableType='App\Models\EntityName' commentableId=1 
                    ownerId=1 positionRendered='Position 01' datetime='2022-01-01 01:02:03' content="Text 01"
                    allowedToDelete=1
                    />
                    {{-- allowed_change_owner=1
                    <x-renderer.comment2 comment01Name="comment01" rowIndex=1 category=6 commentId=11 
                    commentableType='App\Models\EntityName' commentableId=1 
                    ownerId=2 positionRendered='Position 02' datetime='2022-01-02 01:02:03' content="Text 02"
                    allowedChangeOwner=1
                    /> --}}
                    {{-- allowed_attachment=1
                    <x-renderer.comment2 comment01Name="comment01" rowIndex=2 category=6 commentId=12 
                    commentableType='App\Models\EntityName' commentableId=1 
                    ownerId=3 positionRendered='Position 03' datetime='2022-01-03 01:02:03' content="Text 03"
                    allowedAttachment=1
                    /> --}}
                    readonly=1
                    <x-renderer.comment2 comment01Name="comment01" rowIndex=3 category=6 commentId=13
                    commentableType='App\Models\EntityName' commentableId=1 
                    ownerId=4 positionRendered='Position 04' datetime='2022-01-04 01:02:03' content="Text 04"
                    readonly=1
                    />
            </x-renderer.card>
            @dump($_GET['comment02'] ??[])
            <x-renderer.card title="Comment 02">
                <x-renderer.comment2 comment01Name="comment02" rowIndex=0 category=7 commentId=21 
                    commentableType='App\Models\EntityName' commentableId=1 
                    ownerId=11 positionRendered='Position 04' datetime='2022-01-11 01:02:03' content="Text 04"
                    />
                <x-renderer.comment2 comment01Name="comment02" rowIndex=1 category=7 commentId=22 
                    commentableType='App\Models\EntityName' commentableId=1 
                    ownerId=12 positionRendered='Position 05' datetime='2022-01-12 01:02:03' content="Text 05"
                    />
                <x-renderer.comment2 comment01Name="comment02" rowIndex=2 category=7 commentId=23 
                    commentableType='App\Models\EntityName' commentableId=1 
                    ownerId=13 positionRendered='Position 06' datetime='2022-01-13 01:02:03' content="Text 06"
                    />
            </x-renderer.card>
            <x-renderer.button htmlType='submit' type='primary'>Update</x-renderer.button>
        </form>
    </x-renderer.card>
</div>
