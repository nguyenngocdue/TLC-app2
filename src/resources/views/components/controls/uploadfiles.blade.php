{{-- @dump($attachmentData) --}}
<div class=" bg-white rounded-lg border">
    <x-renderer.smart-attachment readonly={{false}} destroyable={{true}} categoryName={{$name}} showToBeDeleted={{$showToBeDeleted}} :attachmentData="$attachmentData" action={{$action}} labelName={{$labelName}} path={{$path}} />
</div>
