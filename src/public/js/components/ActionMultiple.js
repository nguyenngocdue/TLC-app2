const actionCheckboxAll = (type) => {
    queryStr = "input:checkbox[name='" + type + "[]']"
    $(queryStr).prop('checked', !$(queryStr).prop('checked'))
}

const actionConfirmObject = (checkedValues, action) => ({
    title: 'Are you sure?',
    text:
        'You are about to ' +
        action +
        ' the following item(s): ' +
        checkedValues.join(', '),
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'OK',
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
})

const actionSuccessObject = (message, action) => ({
    title: action + ' Successfully',
    text: message,
    icon: 'success',
})

const actionFailObject = (message, action) => ({
    title: action + ' Failed',
    text: message,
    icon: 'warning',
})

const actionNotFoundObject = (action) => ({
    title: 'Not Found',
    text: 'Please select at least one item for ' + action + '.',
    icon: 'warning',
})

const ajaxSendRequest = (type = 'get', url, strIds, name = 'Duplicated') => {
    $.ajax({
        type: type,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        url: url,
        data: 'ids=' + strIds,
        success: function (response) {
            if (response.success) {
                if (response.hits.length > 0) {
                    var duplicateSuccess =
                        response.hits.length > 0 ? response.hits : 'empty'
                    var message = `Document ID(s): ${duplicateSuccess}`
                    Swal.fire(actionSuccessObject(message, name)).then(() =>
                        setTimeout(location.reload.bind(location), 500)
                    )
                }
                if (response.meta[0].length > 0) {
                    var duplicateFail =
                        response.meta[0].length > 0 ? response.meta[0] : 'empty'
                    var message = `Document ID: ${duplicateFail}. Please check setting and permission!`
                    Swal.fire(actionFailObject(message, name)).then(() =>
                        setTimeout(location.reload.bind(location), 500)
                    )
                }
            } else {
                Swal.fire(actionFailObject(response.message, name)).then(() =>
                    setTimeout(location.reload.bind(location), 500)
                )
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            Swal.fire(actionFailObject(jqXHR.responseJSON.message, name)).then(
                () => setTimeout(location.reload.bind(location), 500)
            )
        },
    })
}

const getCheckedValues = (type) => {
    var checkedValues = []
    queryStr = "input:checkbox[name='" + type + "[]']"
    $(queryStr).each(function () {
        if ($(this).prop('checked')) {
            checkedValues.push($(this).val())
        }
    })
    return checkedValues.filter(($item) => $item !== 'none')
}

const actionMultiple = (
    type,
    url,
    actionFunc = 'duplicated',
    checkedValues = []
) => {
    switch (actionFunc) {
        case 'deleted':
            method = 'delete'
            nameConfirm = 'delete'
            nameSendRequest = 'Deleted'
            nameNotFound = 'deletion'
            break
        case 'restored':
            method = 'post'
            nameConfirm = 'restore'
            nameSendRequest = 'Restored'
            nameNotFound = 'restoration'
            break
        default:
            method = 'post'
            nameConfirm = 'duplicate'
            nameSendRequest = 'Duplicated'
            nameNotFound = 'duplication'
            break
    }
    if (checkedValues.length == 0) {
        checkedValues = getCheckedValues(type)
    }
    if (typeof checkedValues === 'string') {
        checkedValues = [checkedValues]
    }
    if (checkedValues.length > 0) {
        var strIds = checkedValues.join(',') ?? ''

        Swal.fire(actionConfirmObject(checkedValues, nameConfirm)).then(
            (result) => {
                if (result.isConfirmed) {
                    ajaxSendRequest(method, url, strIds, nameSendRequest)
                }
            }
        )
    } else {
        Swal.fire(actionNotFoundObject(nameNotFound))
    }
}
