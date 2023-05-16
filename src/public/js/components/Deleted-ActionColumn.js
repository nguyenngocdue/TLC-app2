const actionDuplicated = (obj) => {
    const url = obj['urlDuplicate']
    const id = obj['id']
    Swal.fire(actionConfirmObject([id], 'duplicate')).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'get',
                url: url,
                success: function (response) {
                    if (response.success) {
                        Swal.fire(
                            actionSuccessObject(response.message, 'Duplicated')
                        )
                        setTimeout(location.reload.bind(location), 500)
                    } else {
                        Swal.fire(
                            actionFailObject(response.message, 'Duplicated')
                        )
                        setTimeout(location.reload.bind(location), 500)
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    Swal.fire(actionFailObject(textStatus, 'Duplicate'))
                    setTimeout(location.reload.bind(location), 500)
                },
            })
        }
    })
}

const actionRestored = (obj) => {
    const url = obj['urlRestore']
    const id = obj['id']
    Swal.fire(actionConfirmObject([id], 'restore')).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'get',
                url: url,
                success: function (response) {
                    if (response.success) {
                        Swal.fire(
                            actionSuccessObject(response.message, 'Restored')
                        )
                        setTimeout(location.reload.bind(location), 500)
                    } else {
                        Swal.fire(
                            actionFailObject(response.message, 'Restored')
                        )
                        setTimeout(location.reload.bind(location), 500)
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    Swal.fire(actionFailObject(textStatus, 'Restored'))
                    setTimeout(location.reload.bind(location), 500)
                },
            })
        }
    })
}

const actionDeleted = (obj) => {
    const url = obj['urlDestroy']
    const id = obj['id']
    Swal.fire(actionConfirmObject([id], 'delete')).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'delete',
                url: url,
                success: function (response) {
                    if (response.success) {
                        Swal.fire(
                            actionSuccessObject(response.message, 'Deleted')
                        )
                        setTimeout(location.reload.bind(location), 500)
                    } else {
                        Swal.fire(actionFailObject(response.message, 'Deleted'))
                        setTimeout(location.reload.bind(location), 500)
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    Swal.fire(
                        actionFailObject(jqXHR.responseJSON.message, 'Delete')
                    )
                    setTimeout(location.reload.bind(location), 500)
                },
            })
        }
    })
}
