//Setup for any Ajax requests need to login
$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), }, })
function callApiStoreEmpty(url, data, meta, callback = null) {
    $.ajax({
        type: 'post',
        url,
        data: { meta, lines: data },
        success: function (response) {
            if (callback) {
                callback(response);
            } else {
                toastr.success(response.message);
                window.location.href = response.hits[0]['redirect_edit_href'];
            }
        },
        error: function (jqXHR) {
            toastr.error(jqXHR.responseJSON.message);
        },
    })
}
function listenerSubmitForm(idForm) {
    var form = document.getElementById(idForm);
    form.addEventListener('submit', function (event) {
        $('button').prop('disabled', true);
        $('input[component="controls/number2"]').each(function () {
            var currentValue = $(this).val();
            var cleanedValue = currentValue.replace(/[^0-9.\-]/g, '');
            $(this).val(cleanedValue);
        });
    });
}

const parseNumber2 = (id, initValue) => {
    const inputNumber = document.getElementById(id);
    const formatterFn = (value) => {
        // if (value.includes('.')) {
            const [a, b] = value.split(".")
            return a.replace(/^0+(?=\d)/, '').replace(/\B(?=(\d{3})+(?!\d))/g, ',') + (typeof b == 'string' ? ('.' + b.replace(/0+$/, '')) : "")
        // } else {
        //     return value.replace(/\B(?=(\d{3})+(?!\d))/g, ',').replace(/^0+(?=\d)/, '');
        // }
        
    }
    const parserFn = value => value.replace(/\$\s?|(,*)/g, '');
    const setCursorPosition = (el, pos) => {
        if (el.setSelectionRange) {
            el.focus();
            el.setSelectionRange(pos, pos);
        } else if (el.createTextRange) {
            const range = el.createTextRange();
            range.collapse(true);
            range.moveEnd('character', pos);
            range.moveStart('character', pos);
            range.select();
        }
    };
    inputNumber.addEventListener('blur', event => {
        const inputValue = event.target.value;
        // const oldCursorPosition = inputNumber.selectionStart;
        const parsedValue = parserFn(inputValue);
        if (!isNaN(parsedValue)) {
            formattedValue = formatterFn(parsedValue);
            event.target.value = formattedValue;
            // const diffLength = formattedValue.length - inputValue.length;
            // const newCursorPosition = oldCursorPosition + diffLength;
            // setCursorPosition(inputNumber, newCursorPosition);
        }
    });
    inputNumber.value = formatterFn(initValue);
}
