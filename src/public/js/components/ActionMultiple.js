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
                            var deleteSuccess =
                                response.hits.length > 0
                                    ? response.hits
                                    : 'empty'
                            var deleteFail =
                                response.meta[0].length > 0
                                    ? response.meta[0]
                                    : 'empty'
                            var message = `Delete Success Document ID: ${deleteSuccess} and Delete Fail Document ID: ${deleteFail}`
                            Swal.fire('Deleted Success', message, 'success')
                            setTimeout(location.reload.bind(location), 1000)
                        } else {
                            Swal.fire(
                                'Deleted Fail',
                                response.message,
                                'warning'
                            )
                            setTimeout(location.reload.bind(location), 2000)
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR)
                        Swal.fire(
                            'Delete Fail!',
                            'Permission denied , please check your permissions!',
                            'warning'
                        )
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
                        console.log(response)
                        if (response.success) {
                            var duplicateSuccess =
                                response.hits.length > 0
                                    ? response.hits
                                    : 'empty'
                            var duplicateFail =
                                response.meta[0].length > 0
                                    ? response.meta[0]
                                    : 'empty'
                            var message = `Duplicate Success Document ID: ${duplicateSuccess} and Duplicate Fail Document ID: ${duplicateFail}`
                            Swal.fire('Duplicate Success', message, 'success')
                            setTimeout(location.reload.bind(location), 1000)
                        } else {
                            Swal.fire(
                                'Duplicate Fail',
                                response.message,
                                'warning'
                            )
                            setTimeout(location.reload.bind(location), 2000)
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR)
                        Swal.fire(
                            'Duplicate Fail!',
                            'Permission denied , please check your permissions!',
                            'warning'
                        )
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
