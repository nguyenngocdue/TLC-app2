<x-social.card>
    <x-social.post-form-header modalId="new-post" />
    <x-social.post-form-footer modalId="new-post" />
    <form action="{{route('posts.store')}}" id="form-new-post" method="POST" enctype="multipart/form-data">
        @csrf
        @method('POST')
        <x-social.post-form-input modalId="new-post"/>
    </form>
</x-social.card>