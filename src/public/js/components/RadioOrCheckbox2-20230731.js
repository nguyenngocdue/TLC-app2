const radioOrCheckboxSelectAll = (id) => {
    queryStr = "input:checkbox[name='" + id + "[]']"
    $(queryStr).prop('checked', true)
}

const radioOrCheckboxDeselectAll = (id) => {
    queryStr = "input:checkbox[name='" + id + "[]']"
    $(queryStr).prop('checked', false)
}

// This is to change order of user by employee_id
// const radioOrCheckboxChangeOrder = (id, table, parentId) => {
//     const dataSourceDropdown = k[table]
//     const sortedDataSource = dataSourceDropdown.sort((a, b) => (a.description).localeCompare(b.description))
//     reloadDataToDropdown2(id, 'id', sortedDataSource, [], /* letUserChooseWhenOneItem */)
//     if (Array.isArray(listenersOfDropdown2)) {
//         listenersOfDropdown2.forEach((listener) => {
//             if (listener.triggers.includes(parentId) && listener.listen_action === 'reduce') {
//                 // console.log("I am a trigger of reduce, I have to trigger myself when form load [id]", )
//                 getEById(parentId).trigger('change', { onLoad: true })
//             }
//         })
//     }
// }