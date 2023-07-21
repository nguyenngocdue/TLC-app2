//Setup for any Ajax requests need to login
$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), }, })
function callApiStoreEmpty(url, data, callback = null) {
    $.ajax({
        type: 'post',
        url,
        data: { lines: data },
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
    // var id = @json($name);
    // let initValue = @json(old($name, $value));
    const inputNumber = document.getElementById(id);
    // const formatterSubmitFn = value => value.replace(/,/g, '');
    const formatterFn = (value) => {
        const [a, b] = value.split(".")
        return a.replace(/\B(?=(\d{3})+(?!\d))/g, ',') + (typeof b == 'string' ? ('.' + b) : "")
    }
    const parserFn = value => value.replace(/\$\s?|(,*)/g, '');
    inputNumber.addEventListener('input', event => {
        const inputValue = event.target.value;
        const parsedValue = parserFn(inputValue);
        if (!isNaN(parsedValue)) {
            event.target.value = formatterFn(parsedValue);
        }
    });
    inputNumber.value = formatterFn(initValue);
}
