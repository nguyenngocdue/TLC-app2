//Setup for any Ajax requests need to login
function listenerSubmitForm(idForm) {
    var form = document.getElementById(idForm);
    form.addEventListener('submit', function (event) {
        $('button').prop('disabled', true);
        $elements = ['input[component="controls/number2"]','input[component="editable/number4"'];
        $elements.forEach((element) => numberRemoveCommaByElements(element));
        
    });
}
const numberRemoveCommaByElements = (nameElement) => {
    $(nameElement).each(function () {
        var currentValue = $(this).val();
        var cleanedValue = numberRemoveComma(currentValue);
        $(this).val(cleanedValue);
    });
}
const removeLetters = (number) => number.replace(/[^\d.-]/g, '');

const parseNumber2 = (id, initValue) => {
    const inputNumber = $("[id='" + id + "']");
    const formatterFn = (value) => {
        if (value !== null) {
            // value = removeLetters(value);
            if (typeof value === 'number') value = value + '';
            const [a, b] = value.split(".")
            const formattedValue = a.replace(/^0+(?=\d)/, '').replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            if (typeof b == 'string' && (b*1) !== 0 && b.length > 0) {
                return formattedValue + '.' + b.replace(/0+$/, '');
            }
            return formattedValue;
        }
    }
    const parserFn = value => value.replace(/\$\s?|(,*)/g, '');
    inputNumber.on('blur change', () => {
        const inputValue = inputNumber.val();
        const parsedValue = parserFn(inputValue);
        console.log(parsedValue)
        console.log(!isNaN(parsedValue))
        if (!isNaN(parsedValue)) {
            formattedValue = formatterFn(parsedValue);
            inputNumber.val(formattedValue);
        }
    });
    inputNumber.val(formatterFn(initValue));
}
const numberRemoveComma = (number) => number.replace(/[^0-9.\-]/g, '')