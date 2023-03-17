const radioOrCheckboxSelectAll = (id) => {
    queryStr = "input:checkbox[name='" + id + "[]']"
    $(queryStr).prop('checked', true)
    // if (debugSetValue) console.log('Unchecked all', queryStr)
}

const radioOrCheckboxDeselectAll = (id) => {
    queryStr = "input:checkbox[name='" + id + "[]']"
    $(queryStr).prop('checked', false)
    // if (debugSetValue) console.log('Unchecked all', queryStr)
}