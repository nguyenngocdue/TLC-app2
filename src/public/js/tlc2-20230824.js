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
                window.location.href = response.hits[0]['redirect_edit_href']
            }
        },
        error: function (jqXHR) {
            toastr.error(jqXHR.responseJSON.message)
        },
    })
}

const callApiCloneTemplate = (url, data, meta, callback = null) =>
    callApiStoreEmpty(url, data, meta, callback)

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
const openAnotherLink = (linkId) => {
    const a = document.getElementById(linkId)
    if (a) {
        a.click()
    }
}

//This will stop user to click "Back" on browser
//This is to stop user from create 2 documents from 2 tabs of view all matrix
//But if we constrained the unique key in database, this seems to be not necessary
// setTimeout(() => { window.history.forward(); }, 0);
// window.onunload = () => { null };

function saveAndClose() {
    $('[id="form-upload"]').append(
        '<input type="hidden" name="saveAndClose" value="true">'
    )
    // $('[id="form-upload"]').submit()
}

function approveAll(url, listIdPendingApproval) {
    textListIdPendingApproval = listIdPendingApproval.toString();
    Swal.fire({
        title: 'Are you sure?',
        text: `You want approve all list id: ${textListIdPendingApproval} this!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: `Yes, I want!`,
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'post',
                url,
                data: { ids: listIdPendingApproval },
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
    })
}
