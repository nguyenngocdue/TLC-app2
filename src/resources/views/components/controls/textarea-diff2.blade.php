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
    // var value = [];
    var value = '';
    var editorContentInput = document.getElementById('editor_content_' + name);
    diff.forEach(function(part){
      var highlightedText = applyHighlights(part);
      // value.push(highlightedText);
      value += highlightedText;
    });
    $valueEditor.html(process(value));
    function applyHighlights(part) {
      if(isModeDraft){
        if(part.added)
          return '<mark class="marker-green">' + part.value + '</mark>';
        else if (typeof part.added === "undefined" && part.removed)
          return '';
        else return part.value;
      }else{
        if(part.removed)
          return '<mark class="marker-pink">' + part.value + '</mark>';
        else if (typeof part.removed === "undefined" && part.added)
          return '';
        else return part.value;
      }
    }
    function handleFormatValue(lines,index,result){
      if(lines[index-1]?.includes("<mark")){
        return result + handleFormatValueByStr(lines[index],true);
      }else{
        const splitArray = lines[index]?.split("\r\n");
        splitArray?.forEach((line ,index) => {
              if (line === "")
              result += '<p><br data-cke-filler="true"></p>';
              else 
              result += `<p>${line}</p>`;
        });
        return result;
      }
    }
    function handleFormatValueByStr(str,isNoneAddTagP = false){
      const lines = str.split("\r\n");
      const processLined = lines.map((line) => {
        if(line === "")
          return '<p><br data-cke-filler="true"></p>';
        else
          if(isNoneAddTagP) return `${line}`;
          return `<p>${line}</p>`;
      })
      return processLined.join("");
    }
    function regexExecGetContent(str){
      let regex = /(<mark\b[^>]*>)([\s\S]*?)<\/mark>/g;
      let matches = [];
      // Find all matches
      let match;
      while ((match = regex.exec(str)) !== null) {
          matches.push({
              tag: match[1], 
              content: match[2]     // Content inside the <mark> tag
          });
      }
      var result = "";
      matches.forEach((match) => {
          result += match.tag + handleFormatValueByStr(match.content) + "</mark>";
      });
      return result;
    }
    function handleFormatValueHasMark(str){
      if(!str.includes("\r\n")) return str;
      return regexExecGetContent(str);
    }
    function process(str){
      let regex = /(<mark\b[^>]*>[\s\S]*?<\/mark>)/g;
      let lines = str.split(regex);
      let result = "";
      const processedLines = lines.forEach((line ,index) => {
        if(line == " ") result += line;
        else if (line == "") result+="";
        else if(line.includes("</mark>"))
          result += handleFormatValueHasMark(line);
        else
          result = handleFormatValue(lines,index,result);
      });
      console.log(result);
      return result;
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