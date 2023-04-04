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
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes,delete it!',
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
                                var message = `Deleted Success Document ID: ${deleteSuccess}`
                                Swal.fire(
                                    'Deleted Success',
                                    message,
                                    'success'
                                ).then(() => {
                                    setTimeout(
                                        location.reload.bind(location),
                                        1000
                                    )
                                })
                            }
                            if (response.meta[0].length > 0) {
                                var deleteFail =
                                    response.meta[0].length > 0
                                        ? response.meta[0]
                                        : 'empty'
                                var message = `Deleted Fail Document ID: ${deleteFail}. Please check setting and permission!`
                                Swal.fire(
                                    'Deleted Fail',
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
                                'Deleted Fail',
                                response.message,
                                'warning'
                            ).then(() => {
                                setTimeout(location.reload.bind(location), 1000)
                            })
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR)
                        Swal.fire(
                            'Delete Fail!',
                            'Permission denied , please check your permissions!',
                            'warning'
                        ).then(() => {
                            setTimeout(location.reload.bind(location), 1000)
                        })
                    },
                })
            }
        })
    } else {
        toastr.warning(
            'Please checked line if you want using feature delete that',
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
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes,duplicate it!',
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
                                var message = `Duplicate Success Document ID: ${duplicateSuccess}`
                                Swal.fire(
                                    'Duplicate Success',
                                    message,
                                    'success'
                                ).then(() => {
                                    setTimeout(
                                        location.reload.bind(location),
                                        1000
                                    )
                                })
                            }
                            if (response.meta[0].length > 0) {
                                var duplicateFail =
                                    response.meta[0].length > 0
                                        ? response.meta[0]
                                        : 'empty'
                                var message = `Duplicate Fail Document ID: ${duplicateFail}. Please check setting and permission!`
                                Swal.fire(
                                    'Duplicate Fail',
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
                                'Duplicate Fail',
                                response.message,
                                'warning'
                            ).then(() => {
                                setTimeout(location.reload.bind(location), 1000)
                            })
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR)
                        Swal.fire(
                            'Duplicate Fail!',
                            'Permission denied , please check your permissions!',
                            'warning'
                        ).then(() => {
                            setTimeout(location.reload.bind(location), 1000)
                        })
                    },
                })
            }
        })
    } else {
        toastr.warning(
            'Please checked line if you want using feature duplicate that',
            'Warning'
        )
    }
}
