const actionCheckboxAll = (type) => {
    queryStr = "input:checkbox[name='" + type + "[]']"
    $(queryStr).prop('checked', !$(queryStr).prop('checked'))
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
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to delete the following item(s): " + checkedValues.join(', '),
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'OK',
        }).then((result) => {
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
                                    'Deleted Successfully',
                                    message,
                                    'success'
                                ).then(() => {
                                    setTimeout(
                                        location.reload.bind(location),
                                        500
                                    )
                                })
                            }
                            if (response.meta[0].length > 0) {
                                var deleteFail =
                                    response.meta[0].length > 0
                                        ? response.meta[0]
                                        : 'empty'
                                var message = `Document ID(s): ${deleteFail}. Please check settings and permissions!`
                                Swal.fire(
                                    'Failed to delete',
                                    message,
                                    'warning'
                                ).then(() => {
                                    setTimeout(
                                        location.reload.bind(location),
                                        1000
                                    )
                                })
                            }
                        } else {
                            Swal.fire(
                                'Failed to delete',
                                response.message,
                                'error'
                            ).then(() => {
                                setTimeout(location.reload.bind(location), 500)
                            })
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR)
                        Swal.fire(
                            'Failed to delete',
                            'Permission denied, please check your permissions!',
                            'error'
                        ).then(() => {
                            setTimeout(location.reload.bind(location), 500)
                        })
                    },
                })
            }
        })
    } else {
        toastr.warning(
            'Please select at least one item for deletion.',
            'Warning'
        )
    }
}
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
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to duplicate the following item(s): " + checkedValues.join(', '),
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'OK',
        }).then((result) => {
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
                                    'Duplicated Successfully',
                                    message,
                                    'success'
                                ).then(() => {
                                    setTimeout(
                                        location.reload.bind(location),
                                        500
                                    )
                                })
                            }
                            if (response.meta[0].length > 0) {
                                var duplicateFail =
                                    response.meta[0].length > 0
                                        ? response.meta[0]
                                        : 'empty'
                                var message = `Document ID: ${duplicateFail}. Please check setting and permission!`
                                Swal.fire(
                                    'Failed to duplicate',
                                    message,
                                    'warning'
                                ).then(() => {
                                    setTimeout(
                                        location.reload.bind(location),
                                        500
                                    )
                                })
                            }
                        } else {
                            Swal.fire(
                                'Failed to duplicate ',
                                response.message,
                                'warning'
                            ).then(() => {
                                setTimeout(location.reload.bind(location), 500)
                            })
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR)
                        Swal.fire(
                            'Failed to duplicate',
                            'Permission denied, please check your permissions!',
                            'error'
                        ).then(() => {
                            setTimeout(location.reload.bind(location), 500)
                        })
                    },
                })
            }
        })
    } else {
        toastr.warning(
            'Please select at least one item for duplication.',
            'Warning'
        )
    }
}
