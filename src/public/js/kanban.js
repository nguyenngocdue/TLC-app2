const setValue = (sortable) => {
    var order = sortable.toArray()
    console.log(order, sortable.el)
}

const onClickToEdit = (id, lbl_type, txt_type) => {
    const lbl = "#" + lbl_type + "_" + id
    const txt = "#" + txt_type + "_" + id
    // console.log("Hide", lbl, "show", txt)
    $(lbl).hide()
    $(txt).show().select()
}

const onClickToCommit = (id, lbl_type, txt_type) => {
    const lbl = "#" + lbl_type + "_" + id
    const txt = "#" + txt_type + "_" + id
    // console.log("Show", lbl, "hide", txt)
    $(lbl).show()
    $(txt).hide()
}

const getCharactersAfterLastUnderscore = (str) => (lastUnderscoreIndex = str.lastIndexOf('_') !== -1) ? str.substring(lastUnderscoreIndex + 1) : str;

const onEnd = (e, route, category) => {
    const { /*from,*/ to, item } = e
    // const category = $('#category').val()
    // console.log("To:", to.id, "itemId:", item.id, "Cat:", category)
    const itemId = getCharactersAfterLastUnderscore(item.id)
    const newParentId = getCharactersAfterLastUnderscore(to.id)
    $.ajax({
        method: "POST",
        url: route,
        data: { category, itemId, newParentId },
        success: function (response) {
            // toastr.success(response.message)
        },
        error: function (jqXHR) {
            toastr.error(jqXHR.responseJSON.message)
        },
    })
}

const kanbanInit1 = (prefix, columns, route, category) => {
    for (let i = 0; i < columns.length; i++) {
        const itemName = prefix + columns[i]
        // console.log("making kanban for", itemName)
        var el = document.getElementById(itemName);
        if (el === null) {
            console.error("EL", itemName, "is NULL")
            continue
        }
        Sortable.create(el, {
            animation: 150,
            group: prefix,
            // store: {
            //     set: setValue,
            // },

            // setData: (dataTransfer, dragEl) => dataTransfer.setData('Text', dragEl.textContent),
            // onChoose: (e) => console.log("onChoose", e, e.oldIndex),
            // onUnchoose: (e) => console.log("onUnchoose", e, e.oldIndex),
            // onStart: (e) => console.log("onStart", e, e.oldIndex),
            onEnd: (e) => onEnd(e, route, category),
            // onAdd: (e) => console.log("onAdd", e.item),
            // onUpdate: (e) => console.log("onUpdate", e.item),
            // onSort: (e) => console.log("onSort", e.item),
            // onRemove: (e) => console.log("onRemove", e.item),
            // onFilter: (e) => console.log("onFilter", e.item),
            // onMove: (e, originalEvent) => console.log("onMove", e.item),
            // onClone: (e) => console.log("onClone", e.item, e.clone),
            // onChange: (e) => console.log("onChange", e.newIndex),

        });
    }
}

// const kanbanInit = (columns, route) => {

//     const wrappers = ["wrapper_0",]
//     for (let i = 0; i < wrappers.length; i++) {
//         const rowName = wrappers[i]
//         var el = document.getElementById(rowName);
//         Sortable.create(el, {
//             animation: 150,
//             group: 'wrapperGroup',
//         });
//     }

//     const rows = ["row_0", "row_1", "row_2"]
//     for (let i = 0; i < rows.length; i++) {
//         const rowName = rows[i]
//         var el = document.getElementById(rowName);
//         Sortable.create(el, {
//             animation: 150,
//             group: 'rowGroup',
//         });
//     }

//     for (let i = 0; i < columns.length; i++) {
//         const itemName = "column_" + columns[i]
//         var el = document.getElementById(itemName);
//         Sortable.create(el, {
//             animation: 150,
//             group: 'columnGroup',
//             // store: {
//             //     set: setValue,
//             // },

//             // setData: (dataTransfer, dragEl) => dataTransfer.setData('Text', dragEl.textContent),
//             // onChoose: (e) => console.log("onChoose", e, e.oldIndex),
//             // onUnchoose: (e) => console.log("onUnchoose", e, e.oldIndex),
//             // onStart: (e) => console.log("onStart", e, e.oldIndex),
//             onEnd: (e) => onEnd(e, route),
//             // onAdd: (e) => console.log("onAdd", e.item),
//             // onUpdate: (e) => console.log("onUpdate", e.item),
//             // onSort: (e) => console.log("onSort", e.item),
//             // onRemove: (e) => console.log("onRemove", e.item),
//             // onFilter: (e) => console.log("onFilter", e.item),
//             // onMove: (e, originalEvent) => console.log("onMove", e.item),
//             // onClone: (e) => console.log("onClone", e.item, e.clone),
//             // onChange: (e) => console.log("onChange", e.newIndex),

//         });
//     }
// }