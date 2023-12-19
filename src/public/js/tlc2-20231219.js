//Setup for any Ajax requests need to login
$.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
})
const callApiStoreEmpty = (url, data, meta, callback = null) => {
    // console.log(url, data, meta);
    $.ajax({
        type: 'post',
        url,
        data: { meta, lines: data },
        success: function (response) {
            if (callback) {
                callback(response)
            } else {
                toastr.success(response.message)
                const href = response?.hits?.[0]?.['redirect_edit_href']
                if (href)
                    window.location.href = href
                else
                    toastr.error("No redirect_edit_href found in hits[0]")
            }
        },
        error: function (jqXHR) {
            toastr.error(jqXHR.responseJSON.message)
        },
    })
}

const callApiCloneTemplate = (url, data, meta, callback = null) => callApiStoreEmpty(url, data, meta, callback)
const callApiGetLines = (url, data, meta, callback = null) => callApiStoreEmpty(url, data, meta, callback)

const makeKi = (k) => {
    const ki = {}
    Object.keys(k).forEach((tableName) => {
        const rows = k[tableName]
        ki[tableName] = {}
        rows.forEach((row) => {
            ki[tableName][row['id']] = row
        })
    })
    return ki
}
const openGallery = (linkId) => {
    const a = document.getElementById(linkId)
    if (a) a.click()
}

//This will stop user to click "Back" on browser
//This is to stop user from create 2 documents from 2 tabs of view all matrix
//But if we constrained the unique key in database, this seems to be not necessary
// setTimeout(() => { window.history.forward(); }, 0);
// window.onunload = () => { null };

const appendSaveAndCloseInput = () => {
    $('[id="form-upload"]').append('<input type="hidden" name="saveAndClose" value="true">')
    // $('[id="form-upload"]').submit()
}

const confirmChange = (ids, nextStatusLabel) => ({
    title: 'Are you sure?',
    html: `This action will change status of ${ids.length} item${ids.length > 1 ? 's' : ''} to <b>${nextStatusLabel}</b>.`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: `Yes`,
    cancelButtonText: `No`,
})

const changeStatusAll = (url, ids, nextStatus, nextStatusLabel) => {
    console.log(url, ids, nextStatus, nextStatusLabel)
    Swal.fire(confirmChange(ids, nextStatusLabel)).then((result) => {
        if (result.isConfirmed) {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'post',
                    url,
                    data: { ids, nextStatus, nextStatusLabel },
                    success: function (response) {
                        if (response.success) {
                            toastr.success(response.message)
                            window.location.reload()
                        }
                    },
                    error: function (jqXHR) {
                        toastr.error(jqXHR.responseJSON.message)
                    },
                })
            }
        }
    })
}

const convertToLocalTimezone = (datetimeString) => {
    const now = new Date();
    const timezoneOffset = now.getTimezoneOffset(); // in minutes

    const datetime = new Date(datetimeString);
    const localTime = new Date(datetime.getTime() - timezoneOffset * 60 * 1000);
    return localTime;
}

const convertToServerTimezoneStr = (datetimeString) => {
    const datetime = new Date(datetimeString);
    const str = datetime.toISOString().substring(0, 19).replace("T", " ");
    return str;
}

// function a() {
//     const x = convertToLocalTimezone('2023-01-01 12:34:56')
//     console.log(x.toISOString())

//     const y = convertToServerTimezone(x)
//     console.log(y)
// }

const newFlatPickrDateTimeParseDate = (dateString, format) => convertToLocalTimezone(dateString)

const flatpickrHandleChange = (name, selectedDates) => {
    // console.log(selectedDates)
    const date = selectedDates[0]
    const result = convertToServerTimezoneStr(date)
    const hiddenInput = document.getElementById('hidden_' + name);
    hiddenInput.value = result;
}

const newFlatPickrDateTime = (id) => {
    // if (name === undefined) console.error("newFlatPickrDateTime missing second argument")
    const element = document.getElementById(id)
    return flatpickr(element, {
        enableTime: true,
        altInput: true,
        altFormat: "d/m/Y H:i",
        dateFormat: 'Y-m-d H:i:S',
        weekNumbers: true,
        time_24hr: true,
        parseDate: newFlatPickrDateTimeParseDate,
        onChange: (selectedDates, dateStr, instance) => {
            flatpickrHandleChange(id, selectedDates)
        }
    });
}

const newFlatPickrTime = (id) => {
    const element = document.getElementById(id)
    return flatpickr(element, {
        noCalendar: true,
        enableTime: true,
        altInput: true,
        altFormat: "H:i",
        dateFormat: 'H:i:S',
        weekNumbers: true,
        time_24hr: true
    });
}

const newFlatPickrDate = (id) => {
    const element = document.getElementById(id)
    return flatpickr(element, {
        // enableTime: true,
        altInput: true,
        altFormat: "d/m/Y",
        dateFormat: 'Y-m-d',
        weekNumbers: true,
        time_24hr: true
    });
}