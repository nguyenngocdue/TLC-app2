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

const actionDuplicateMultiple = (type, url) => {
    var checkedValues = []
    queryStr = "input:checkbox[name='" + type + "[]']"
    $(queryStr).each(function () {
        if ($(this).prop('checked')) {
            checkedValues.push($(this).val())
        }
    })
    checkedValues = checkedValues.filter(($item) => $item !== 'none')
    if (checkedValues.length > 0) {
        var strIds = checkedValues.join(',') ?? ''
        Swal.fire(actionConfirmObject(checkedValues, 'duplicate')).then(
            (result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'post',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content'
                            ),
                        },
                        url: url,
                        data: 'ids=' + strIds,
                        success: function (response) {
                            if (response.success) {
                                if (response.hits.length > 0) {
                                    var duplicateSuccess =
                                        response.hits.length > 0
                                            ? response.hits
                                            : 'empty'
                                    var message = `Document ID(s): ${duplicateSuccess}`
                                    Swal.fire(
                                        actionSuccessObject(
                                            message,
                                            'Duplicated'
                                        )
                                    ).then(() =>
                                        setTimeout(
                                            location.reload.bind(location),
                                            500
                                        )
                                    )
                                }
                                if (response.meta[0].length > 0) {
                                    var duplicateFail =
                                        response.meta[0].length > 0
                                            ? response.meta[0]
                                            : 'empty'
                                    var message = `Document ID: ${duplicateFail}. Please check setting and permission!`
                                    Swal.fire(
                                        actionFailObject(message, 'Duplicate')
                                    ).then(() =>
                                        setTimeout(
                                            location.reload.bind(location),
                                            500
                                        )
                                    )
                                }
                            } else {
                                Swal.fire(
                                    actionFailObject(
                                        response.message,
                                        'Duplicate'
                                    )
                                ).then(() =>
                                    setTimeout(
                                        location.reload.bind(location),
                                        500
                                    )
                                )
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR)
                            Swal.fire(
                                actionFailObject(
                                    'Permission denied, please check your permissions!',
                                    'Duplicate'
                                )
                            ).then(() =>
                                setTimeout(location.reload.bind(location), 500)
                            )
                        },
                    })
                }
            }
        )
    } else {
        Swal.fire(actionNotFoundObject('duplication'))
    }
}

const actionRestoreMultiple = (type, url) => {
    var checkedValues = []
    queryStr = "input:checkbox[name='" + type + "[]']"
    $(queryStr).each(function () {
        if ($(this).prop('checked')) {
            checkedValues.push($(this).val())
        }
    })
    checkedValues = checkedValues.filter(($item) => $item !== 'none')
    if (checkedValues.length > 0) {
        var strIds = checkedValues.join(',') ?? ''
        Swal.fire(actionConfirmObject(checkedValues, 'Restore')).then(
            (result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'post',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content'
                            ),
                        },
                        url: url,
                        data: 'ids=' + strIds,
                        success: function (response) {
                            if (response.success) {
                                if (response.hits.length > 0) {
                                    var restoreSuccess =
                                        response.hits.length > 0
                                            ? response.hits
                                            : 'empty'
                                    var message = `Document ID(s): ${restoreSuccess}`
                                    Swal.fire(
                                        actionSuccessObject(message, 'Restored')
                                    ).then(() =>
                                        setTimeout(
                                            location.reload.bind(location),
                                            500
                                        )
                                    )
                                }
                                if (response.meta[0].length > 0) {
                                    var restoreFail =
                                        response.meta[0].length > 0
                                            ? response.meta[0]
                                            : 'empty'
                                    var message = `Document ID: ${restoreFail}. Please check setting and permission!`
                                    Swal.fire(
                                        actionFailObject(message, 'Restore')
                                    ).then(() =>
                                        setTimeout(
                                            location.reload.bind(location),
                                            500
                                        )
                                    )
                                }
                            } else {
                                Swal.fire(
                                    actionFailObject(
                                        response.message,
                                        'Restore'
                                    )
                                ).then(() =>
                                    setTimeout(
                                        location.reload.bind(location),
                                        500
                                    )
                                )
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR)
                            Swal.fire(
                                actionFailObject(
                                    'Permission denied, please check your permissions!',
                                    'Restore'
                                )
                            ).then(() =>
                                setTimeout(location.reload.bind(location), 500)
                            )
                        },
                    })
                }
            }
        )
    } else {
        Swal.fire(actionNotFoundObject('restore'))
    }
}

const actionDeletedMultiple = (type, url) => {
    var checkedValues = []
    queryStr = "input:checkbox[name='" + type + "[]']"
    $(queryStr).each(function () {
        if ($(this).prop('checked')) {
            checkedValues.push($(this).val())
        }
    })
    checkedValues = checkedValues.filter(($item) => $item !== 'none')
    if (checkedValues.length > 0) {
        var strIds = checkedValues.join(',') ?? ''
        Swal.fire(actionConfirmObject(checkedValues, 'delete')).then(
            (result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'delete',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content'
                            ),
                        },
                        url: url,
                        data: 'ids=' + strIds,
                        success: function (response) {
                            if (response.success) {
                                if (response.hits.length > 0) {
                                    var deleteSuccess =
                                        response.hits.length > 0
                                            ? response.hits
                                            : 'empty'
                                    var message = `Document ID(s): ${deleteSuccess}`
                                    Swal.fire(
                                        actionSuccessObject(message, 'Deleted')
                                    ).then(() =>
                                        setTimeout(
                                            location.reload.bind(location),
                                            500
                                        )
                                    )
                                }
                                if (response.meta[0].length > 0) {
                                    var deleteFail =
                                        response.meta[0].length > 0
                                            ? response.meta[0]
                                            : 'empty'
                                    var message = `Document ID(s): ${deleteFail}. Please check settings and permissions!`
                                    Swal.fire(
                                        actionFailObject(message, 'Delete')
                                    ).then(() =>
                                        setTimeout(
                                            location.reload.bind(location),
                                            500
                                        )
                                    )
                                }
                            } else {
                                Swal.fire(
                                    actionFailObject(response.message, 'Delete')
                                ).then(() =>
                                    setTimeout(
                                        location.reload.bind(location),
                                        500
                                    )
                                )
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR)
                            Swal.fire(
                                actionFailObject(
                                    'Permission denied, please check your permissions!',
                                    'Delete'
                                )
                            ).then(() =>
                                setTimeout(location.reload.bind(location), 500)
                            )
                        },
                    })
                }
            }
        )
    } else {
        Swal.fire(actionNotFoundObject('deletion'))
    }
}
