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
    var diff = window.jsdiff.diffWords(one, other);
    var $valueEditor = $('#editor_' + name);
    var value = [];
    var editorContentInput = document.getElementById('editor_content_' + name);
    console.log(diff);
    diff.forEach(function(part){
      var highlightedText = applyHighlights(part);
      value.push(highlightedText);
    });
    // console.log(value);
    console.log(handleFormatValue(value));
    $valueEditor.html(handleFormatValue(value));
    function applyHighlights(part) {
      var content = part.value;
      if(isModeDraft){
        if(part.added)
          return '<mark class="marker-green">' + content + '</mark>';
        else if (typeof part.added === "undefined" && part.removed)
          return "";
        else return content;
      }else{
        if(part.removed)
          return '<mark class="marker-pink">' + content + '</mark>';
        else if (typeof part.removed === "undefined" && part.added)
          return "";
        else return content;
      }
    }
    function convertData(str) {
      var div = document.createElement('div');
      console.log(str);
      div.innerHTML = str;
      div.childNodes.forEach(function(node){
        var content = node.textContent;
        if(node.nodeName === "MARK"){
          node.innerHTML = handleFormatValue(content,true);
        }else{
          var newNode = document.createElement('div');
          newNode.innerHTML = handleFormatValue(content);
          node.parentNode.replaceChild(newNode, node);
        }
      })
      return div.innerHTML;
    }
    function handleFormatValue(arrStr){
      var results = [];
      var index = 0;
      arrStr.forEach(function(str){
        results.push(process(str));
      })
      for(var i = 0; i < results.length;i++){
        if(results[i].includes("<p><mark")){
          if(i != 0){
            var tmp = results[i-1];
            var tmp2 =  results[i+1];
            tmp = tmp.slice(0,-4) + results[i].slice(3).slice(0,-4);
            tmp2 = tmp2.slice(3);
            results[i-1] = tmp;
            results[i+1] = tmp2;
            results.splice(i,1);
          }else{
            results[i] = results[i].slice(3).slice(0,-4);
          }
        }
      }
      return results.join("");
    }
    function process(str){
      const lines = str.split("\n");
      const processedLines = lines.map((line ,index) => {
            if (line === "")
              return index == 0 ? "" : "<p><br data-cke-filler='true'></p>";
            else 
              return `<p>${line}</p>`;
      });
      return processedLines.join("");
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
      })
      .catch( error => {
        console.error( 'There was a problem initializing the editor.', error );
      });
</script>