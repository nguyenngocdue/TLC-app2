<div id="text_editor_{{$name}}" 
    name="{{$name}}" 
    component="controls/text_editor"
    >{!!$value!!}</div>
    <script>
        var isReadOnly = @json($isReadOnly);
        var name = @json($name);
    DecoupledEditor
      .create( document.querySelector( '#text_editor_' + name), {
        extraPlugins: [ highlightToFontColors ],
      })
      .then(editor => {
        editor.isReadOnly = !isReadOnly; // Ensure read-only mode is set after editor initialization
      })
      .catch( error => {
        console.error( 'There was a problem initializing the editor.', error );
      });
</script>