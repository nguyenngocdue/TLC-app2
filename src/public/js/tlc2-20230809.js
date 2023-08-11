//Setup for any Ajax requests need to login
$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), }, })
const callApiStoreEmpty = (url, data, meta, callback = null) => {
    $.ajax({
        type: 'post',
        url,
        data: { meta, lines: data },
        success: function (response) {
            if (callback) {
                callback(response);
            } else {
                toastr.success(response.message);
                window.location.href = response.hits[0]['redirect_edit_href'];
            }
        },
        error: function (jqXHR) {
            toastr.error(jqXHR.responseJSON.message);
        },
    })
}

const callApiCloneTemplate = (url, data, meta, callback = null) => callApiStoreEmpty(url, data, meta, callback)

const makeKi = (k) => {
    const ki = {}
    Object.keys(k).forEach(tableName => {
        const rows = k[tableName]
        ki[tableName] = {}
        rows.forEach(row => {
            ki[tableName][row['id']] = row
        })
    })
    return ki
}
const openAnotherLink = (linkId) => {
    const a = document.getElementById(linkId);
    if (a) {
        a.click();
    }
}
const saveAndRedirectViewAll = (e) => {
    

}