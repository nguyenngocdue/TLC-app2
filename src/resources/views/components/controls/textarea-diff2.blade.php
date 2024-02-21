<div id="toolbar-container_{{$name}}"></div>
<div id="editor_{{$name}}" 
    name="{{$name}}" 
    component="controls/textarea-diff2"
    class="{{$classList}}"
    style="border: 1px solid #d1d5db !important; border-radius: 0.375rem !important;"
    ></div>
<input type="hidden" id="editor_content_{{$name}}" name="{{$name}}">
<script>
    var name = @json($name);
    var one = @json($value);
    var other = @json($value2);
    var isModeDraft = @json($isModeDraft);
    var placeholder = @json($placeholder);
    var diff = window.jsdiff.diffChars(one, other);
    var $valueEditor = $('#editor_' + name);
    var value = "";
    var editorContentInput = document.getElementById('editor_content_' + name);
    console.log(diff);
    diff.forEach(function(part){
      var highlightedText = applyHighlights(part);
      value += highlightedText;
      });
    $valueEditor.html(value);
    function applyHighlights(part) {
      if(isModeDraft){
        if(part.added)
          return '<mark class="marker-green">' + part.value + '</mark>';
        else if (typeof part.added === "undefined" && part.removed)
          return "";
        else return part.value;
      }else{
        if(part.removed)
          return '<mark class="marker-pink">' + part.value + '</mark>';
        else if (typeof part.removed === "undefined" && part.added)
          return "";
        else return part.value;
      }
    }
    function highlightToFontColors( editor ) {
    const valueMapping = {
      'marker-yellow': '#fdfd77',
      'marker-green': '#63f963',
      'marker-pink': '#fc7999',
      'marker-blue': '#72cdfd',
      'pen-red': '#e91313',
      'pen-green': '#118800'
    };

    for ( const className of Object.keys( valueMapping ) ) {
        editor.conversion.for( 'upcast' ).elementToAttribute( {
          view: {
            name: 'mark',
            classes: [ className ]
          },
          model: {
            key: className.startsWith( 'marker-' ) ? 'fontBackgroundColor' : 'fontColor',
            value: valueMapping[ className ]
          }
        } );
      }
    }
    DecoupledEditor
      .create( document.querySelector( '#editor_' + name ), {
        extraPlugins: [ highlightToFontColors ],
        placeholder: placeholder,
      })
      .then(editor => {
        editor.isReadOnly = !isModeDraft; // Ensure read-only mode is set after editor initialization
        console.log(editor.getData());
      })
      .catch( error => {
        console.error( 'There was a problem initializing the editor.', error );
      });
</script>