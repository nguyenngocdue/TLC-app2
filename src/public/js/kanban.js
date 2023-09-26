const setValue = (sortable) => {
    var order = sortable.toArray()

    console.log(order, sortable.el)
}

function getCharactersAfterLastUnderscore(str) {
    const lastUnderscoreIndex = str.lastIndexOf('_');
    return (lastUnderscoreIndex !== -1) ? str.substring(lastUnderscoreIndex + 1) : str;

}

const onEnd = (e) => {
    const { /*from,*/ to, item } = e
    const category = $('#category').val()
    console.log(to.id, item.id, category)
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

const kanbanInit = (columns, route) => {

    const wrappers = ["wrapper_0",]
    for (let i = 0; i < wrappers.length; i++) {
        const rowName = wrappers[i]
        var el = document.getElementById(rowName);
        Sortable.create(el, {
            animation: 150,
            group: 'wrapperGroup',
        });
    }

    const rows = ["row_0", "row_1", "row_2"]
    for (let i = 0; i < rows.length; i++) {
        const rowName = rows[i]
        var el = document.getElementById(rowName);
        Sortable.create(el, {
            animation: 150,
            group: 'rowGroup',
        });
    }

    for (let i = 0; i < columns.length; i++) {
        const itemName = "column_" + columns[i]
        var el = document.getElementById(itemName);
        Sortable.create(el, {
            animation: 150,
            group: 'columnGroup',
            // store: {
            //     set: setValue,
            // },

            // setData: (dataTransfer, dragEl) => dataTransfer.setData('Text', dragEl.textContent),
            // onChoose: (e) => console.log("onChoose", e, e.oldIndex),
            // onUnchoose: (e) => console.log("onUnchoose", e, e.oldIndex),
            // onStart: (e) => console.log("onStart", e, e.oldIndex),
            onEnd: (e) => onEnd(e, route),
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