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
        @dump($_GET)
        <form action="" method="GET">
            <x-renderer.card title="dataComment=[], not entering other attributes.">
                <x-renderer.comment :dataComment="[]" />
            </x-renderer.card>
            <x-renderer.button htmlType='submit' type='primary'>Update</x-renderer.button>
        </form>
        <br />
        @dump($_GET)
        <form action="" method="GET">
            <x-renderer.card title="readonly ={ { true } }, destroyable ={ { true } }, showToBeDeleted  ={ { true } }, name={ { comment_1 } }">
                <x-renderer.comment readonly={{true}} destroyable={{true}} showToBeDeleted={{true}} name="comment_1" type="department" id="1" :dataComment="$dataComment" />
            </x-renderer.card>
            <x-renderer.button htmlType='submit' type='primary'>Update</x-renderer.button>
        </form>
        <br />
        @dump($_GET)
        <form action="" method="GET">
            <x-renderer.card title="readonly={ { false } }, destroyable={ { false } }, showToBeDeleted={ { false } }, name={ { comment_1 } }">
                <x-renderer.comment readonly={{false}} destroyable={{false}} showToBeDeleted={{false}} name="comment_1" type="department" id="1" :dataComment="$dataComment" />
            </x-renderer.card>
            <x-renderer.button htmlType='submit' type='primary'>Update</x-renderer.button>
        </form>
        <br />
        @dump($_GET)
        <form action="" method="GET">
            <x-renderer.card title="readonly={ { false } }, destroyable={ { true } }, showToBeDeleted={ { false } }, name={ { comment_1 } }, attachmentData={ { $attachmentData } } ">
                <x-renderer.comment readonly={{false}} showToBeDeleted={{false}} destroyable={{true}} name="comment_1" type="department" id="1" :dataComment="$dataComment" :attachmentData="$attachmentData" />
            </x-renderer.card>
            <x-renderer.button htmlType='submit' type='primary'>Update</x-renderer.button>
        </form>
    </x-renderer.card>
</div>
