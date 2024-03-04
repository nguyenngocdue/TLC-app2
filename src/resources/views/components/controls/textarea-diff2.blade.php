<div id="toolbar-container_{{$name}}"></div>
@if($mode != 'draft')
    <span class="no-print flex text-pink-600 justify-center items-center">Original</span>
@else
    <span class="no-print flex text-green-600 justify-center items-center">Editable</span>
@endif
<div id="editor_{{$name}}" 
    name="{{$name}}" 
    component="controls/textarea-diff2"
    class="{{$classList}}"
    style="border: 1px solid #d1d5db !important; border-radius: 0.375rem !important;"
    ></div>
@if($mode != 'draft')
  <input type="hidden" id={{$name}} name="{{$name}}" value="{{$value}}">
@else
<input type="hidden" id="editor_content_{{$name}}" name="{{$name}}">
@endif
<script>
    var name = @json($name);
    var one = @json($value);
    var other = @json($value2);
    var isModeDraft = @json($isModeDraft);
    var placeholder = @json($placeholder);
    var diff = window.jsdiff.diffWords(one, other);
    var $valueEditor = $('#editor_' + name);
    var str = '';
    var editorContentInput = document.getElementById('editor_content_' + name);
    diff.forEach(function(part){
      var highlightedText = applyHighlights(part);
      str += highlightedText;
    });
    $valueEditor.html(handleString(str));
    function applyHighlights(part) {
      if(isModeDraft){
        if(part.added)
          return '<mark class="marker-green">' + (part.value) + '</mark>';
        else if (typeof part.added === "undefined" && part.removed)
          return '';
        else return (part.value);
      }else{
        if(part.removed)
          return '<mark class="marker-pink">' + part.value + '</mark>';
        else if (typeof part.removed === "undefined" && part.added)
          return '';
        else return (part.value);
      }
    }
    function process(str){
      let regex = /(<mark\b[^>]*>[\s\S]*?<\/mark>)/g;
      let lines = str.split(regex);
      let result = "";
      const processedLines = lines.forEach((line ,index) => {
        if(line.includes('flag="true"'))
          // result += handleFormatValueHasMark(line);
          // result += regexExecGetContent(line);
          // result += 'h';
          result += '';
        else if(line.includes('<mark'))
          // result = result.slice(0,-4) + line ;
          result += 'h';
        else
          result += handleFormatValue2(line);
      });
      // console.log(result);
      return result;
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
      if(matches.length == 0) return handleString(str);
      var result = "";
      matches.forEach((match) => {
          result += match.tag + handleString(match.content) + "</mark>"
      });
      return result;
    }
    function handleFormatValue(arrStr){
      if(arrStr.length == 1){
        return regexExecGetContent(arrStr[0]);
      }else{
        let str = '';
        arrStr.forEach((a) => {
          if(a.startsWith("<mark")){
            str += regexExecGetContent(a);
          }else {
            var lines = a.split("\r\n");
            if(lines[lines.length - 1] == "") lines.pop();
            const processLined = lines.map((line) => {
              if(line === "")
                str += '<p><br data-cke-filler="true"></p>';
              else {
                str += `<p>${line}</p>`;
              }
            })
          }  
        });
        return str;
      }
    }
    function handleString(str){
      if(str.endsWith("\n")){ 
        // str = str.slice(0,-2);
      }
      const lines = str.split("\r\n");
      const processLined = lines.map((line) => {
        if(line === "")
        return '<p><br data-cke-filler="true"></p>';
        else {
          if(countUniqueSubstring(line,'<mark') == countUniqueSubstring(line,'</mark>')){
            return `<p>${line}</p>`;
          }
          // console.log(line);
          return `${line}`;
        }
      })  
      return processLined.join("");
    }
    function countUniqueSubstring(string, substring) {
    const lines = string.split(substring)
    return lines.length;
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