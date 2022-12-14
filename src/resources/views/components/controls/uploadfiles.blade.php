{{-- @dump($attachHasMedia); --}}
<div class=" bg-white rounded-lg border">
    <x-renderer.smart-attachment readonly={{false}} destroyable={{true}} attCategory={{$colName}} showToBeDeleted={{$showToBeDeleted}} :attachmentData="$attachHasMedia" colName={{$colName}} action={{$action}} labelName={{$labelName}} path={{$path}} />
</div>
