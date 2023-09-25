const setValue = (sortable) => {
    var order = sortable.toArray()

    console.log(order, sortable.el)
}

const onEnd = (e) => {
    const { /*from,*/ to, item } = e
    const category = $('#category').val()
    console.log(to.id, item.id, category)
    $.ajax({
        method: "POST",
        url: route,
        data: { category, itemId: item.id, newParentId: to.id },
        success: function (response) {
            // toastr.success(response.message)
        },
        error: function (jqXHR) {
            toastr.error(jqXHR.responseJSON.message)
        },
    })
}

const kanbanInit = (columns, route) => {
    for (let i = 0; i < columns.length; i++) {
        const itemName = columns[i]
        var el = document.getElementById(itemName);
        // if(!el) {
        //   console.log("NULL")
        //   continue;
        // }
        // console.log(itemName)
        var sortable = Sortable.create(el, {
            animation: 150,
            group: 'shared',
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