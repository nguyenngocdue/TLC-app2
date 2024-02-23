//Setup for any Ajax requests need to login
function listenerSubmitForm(idForm) {
    var form = document.getElementById(idForm);
    form.addEventListener('submit', function (event) {
        $('button').prop('disabled', true);
        $elements = ['input[component="controls/number2"]', 'input[component="editable/number4"'];
        $elements.forEach((element) => numberRemoveCommaByElements(element));
        $element2 = [
            'input[component="controls/picker_datetime"]',
            'input[component="controls/picker_month"]',
            'input[component="controls/picker_week"]',
            'input[component="controls/picker_time"]',
            'input[component="controls/picker_date"]',

            'input[component="controls/datepicker3"]',

            'input[component="editable/picker_datetime"]',
            'input[component="editable/picker_month"]',
            'input[component="editable/picker_week"]',
            'input[component="editable/picker_time"]',
            'input[component="editable/picker_date"]',
        ];
        // console.log(window.editors);
        //Add leading ZERO to date or time when user only enter one digit
        $element2.forEach((element) => dataPickerFormatByElements(element));
        //Handle logic textarea differences
        $editorInputs = document.querySelectorAll('div[id^="editor_"]');
        
        $editorInputs.forEach(function(input) {
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
            var updatedHtmlContent = tempElement.innerHTML;

            $editorInputContent = document.querySelector('input[id^="editor_content_'+name+'"]');
            if ($editorInputContent) {
                // if(!(updatedHtmlContent == '<p><br data-cke-filler="true"></p>'))
                    $editorInputContent.value = updatedHtmlContent;
            }
        });
    });
}
const removeParagraphTagsWithoutBreaks = (text) => {
    const regex = /<p>(?:(?!<br\s+(?:data-cke-filler\s*=\s*"true")?\s*\/?>)[\s\S])*<\/p>/g;
    return text.replace(regex, '');
}
const numberRemoveCommaByElements = (nameElement) => {

    $(nameElement).each(function () {
        var currentValue = $(this).val();
        var cleanedValue = numberRemoveComma(currentValue);
        $(this).val(cleanedValue);
    });
}
const dataPickerFormatByElements = (nameElement) => {
    var regex;
    var replacement;
    var callback;
    switch (nameElement) {
        case 'input[component="controls/picker_time"]':
        case 'input[component="editable/picker_time"]':
            regex = /(\d+):(\d+)/g;
            replacement = (match, p1, p2) => {
                return p1.padStart(2, '0') + ':' + p2.padStart(2, '0');
            };
            break;
        case 'input[component="controls/picker_month"]':
        case 'input[component="editable/picker_month"]':
            regex = /^(\d{1})\//;
            replacement = '0$1/';
            break;
        case 'input[component="controls/picker_week"]':
        case 'input[component="editable/picker_week"]':
            regex = /^W(\d{1})\//;
            replacement = 'W0$1/';
            break;
        case 'input[component="controls/datepicker3"]':
        case 'input[component="controls/picker_datetime"]':
        case 'input[component="editable/picker_datetime"]':
        case 'input[component="controls/picker_date"]':
        case 'input[component="editable/picker_date"]':
            callback = (value) => {
                return value.replace(/(\d{1,2})\/(\d{1,2})\//g, (match, p1, p2) => {
                    return p1.padStart(2, '0') + '/' + p2.padStart(2, '0') + '/';
                })
                    .replace(/(\d+):(\d+)/g, (match, p1, p2) => {
                        return p1.padStart(2, '0') + ':' + p2.padStart(2, '0');
                    })
            };
            break;
        default:
            break;
    }
    $(nameElement).each(function () {
        var currentValue = $(this).val();
        if (callback) {
            var formatValue = callback(currentValue);
        } else {
            var formatValue = currentValue.replace(regex, replacement);
        }
        $(this).val(formatValue);
    });

}
const removeLetters = (number) => number.replace(/[^\d.-]/g, '');

const parseNumber2 = (id, initValue, numericScale = 2) => {
    const inputNumber = $("[id='" + id + "']");
    const formatterFn = (value) => {
        if (value !== null) {
            // value = removeLetters(value);
            if (typeof value === 'number') value = value + '';
            const [a, b] = value.split(".")
            const formattedValue = a.replace(/^0+(?=\d)/, '').replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            let formattedValue2 = '';
            if (typeof b == 'string' && (b * 1) !== 0 && b.length > 0) {
                formattedValue2 = (('0.' + b.replace(/0+$/, '')) * 1).toFixed(numericScale);
                const [c, d] = formattedValue2.split(".");
                return formattedValue + '.' + d?.padEnd(numericScale, '0');
            }
            return (numericScale >= 1) ? formattedValue + '.' + ('').padEnd(numericScale, '0') : formattedValue;
        }
    }
    const parserFn = value => value.replace(/\$\s?|(,*)/g, '');
    inputNumber.on('blur change', () => {
        const inputValue = inputNumber.val();
        const parsedValue = parserFn(inputValue);
        // console.log(parsedValue)
        // console.log(!isNaN(parsedValue))
        if (!isNaN(parsedValue)) {
            formattedValue = formatterFn(parsedValue);
            inputNumber.val(formattedValue);
        }
    });
    const result = formatterFn(initValue);
    inputNumber.val(result);
    return result
}
const numberRemoveComma = (number) => number.replace(/[^0-9.\-]/g, '')