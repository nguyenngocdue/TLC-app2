const actionDuplicated = (obj) => {
    // const url = '/' + url + '/' + id
    const url = obj['urlDuplicate']
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
                type: 'get',
                url: url,
                success: function (response) {
                    if (response.success) {
                        Swal.fire(
                            'Duplicated Success',
                            response.message,
                            'success'
                        )
                        setTimeout(location.reload.bind(location), 500)
                    } else {
                        Swal.fire(
                            'Duplicated Fail',
                            response.message,
                            'warning'
                        )
                        setTimeout(location.reload.bind(location), 1500)
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    Swal.fire('Duplicated Fail', textStatus, 'warning')
                    setTimeout(location.reload.bind(location), 1500)
                },
            })
        }
    })
}

const actionDeleted = (obj) => {
    const url = obj['urlDestroy']
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
                url: url,
                success: function (response) {
                    if (response.success) {
                        Swal.fire(
                            'Deleted Success',
                            response.message,
                            'success'
                        )
                        setTimeout(location.reload.bind(location), 500)
                    } else {
                        Swal.fire('Deleted Fail', response.message, 'warning')
                        setTimeout(location.reload.bind(location), 1500)
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    Swal.fire(
                        'Delete Fail!',
                        'Permission denied , please check your permissions!',
                        'warning'
                    )
                    setTimeout(location.reload.bind(location), 1500)
                },
            })
        }
    })
}
