//Setup for any Ajax requests need to login
function listenerSubmitForm(idForm) {
    var form = document.getElementById(idForm);
    form.addEventListener('submit', function (event) {
        $('button').prop('disabled', true);
        elements = ['input[component="controls/number2"]', 'input[component="editable/number4"'];
        elements.forEach((element) => numberRemoveCommaByElements(element));
        element2 = [
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
        element2.forEach((element) => dataPickerFormatByElements(element));
        
        //Handle logic textarea differences
        editorInputs = document.querySelectorAll('div[id^="editor_"]');
        handleSubmitTextAreaDiff(editorInputs)
    });
}
