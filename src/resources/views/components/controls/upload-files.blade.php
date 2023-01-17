{{-- @dump($attachmentData) --}}
<div class=" bg-white rounded-lg border">
    <x-renderer.attachment readonly={{false}} destroyable={{true}} categoryName={{$name}} showToBeDeleted={{$showToBeDeleted}} :attachmentData="$attachmentData" action={{$action}} label={{$label}} path={{$path}} />
</div>
