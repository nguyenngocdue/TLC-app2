const handleSubmitTextAreaDiff = (element) => {
    element.forEach(function(input) {
        var id = input.id;
        var name = id.substring("editor_".length);
        var htmlContent = input.innerHTML;
        var tempElement = document.createElement('div');
        tempElement.innerHTML = htmlContent;
        // Remove <mark> elements while retaining their text content
        var marks = tempElement.querySelectorAll('mark');
        marks.forEach(function(mark) {
            var textNode = document.createTextNode(mark.textContent);
            mark.parentNode.replaceChild(textNode, mark);
        });
        var ps = tempElement.querySelectorAll('p');
        ps.forEach(function(p) {
                var content = p.textContent + "\n";
                var textNode = document.createTextNode(content);
                p.parentNode.replaceChild(textNode, p);
        });
        // Get the updated HTML content without <mark> elements
        var updatedHtmlContent = tempElement.textContent;
        editorInputContent = document.querySelector('input[id^="editor_content_'+name+'"]');
        if (editorInputContent) {
            // if(!(updatedHtmlContent == '<p><br data-cke-filler="true"></p>'))
                editorInputContent.value = updatedHtmlContent;
        }
    });
}
