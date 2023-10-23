const globalInterval = []
const onKanbanAjaxError = (jqXHR) => toastr.error(jqXHR.responseJSON.message)

const getChildPrefix = (prefix) => {
    switch (prefix) {
        case "page_": return "cluster_"
        case "cluster_": return "group_"
        case "group_": return "task_"
        case "bucket_": return "toc_"
        case "toc_group_": return "bucket_"
        default: console.error("Unknown child of prefix", prefix)
    }
}

// const getParentPrefix = (prefix) => {
//     switch (prefix) {
//         case "cluster_": return "page_"
//         case "group_": return "cluster_"
//         case "task_": return "group_"
//         default: console.error("Unknown parent of prefix", prefix)
//     }
// }

const setValue = (sortable, url, prefix) => {
    var order = sortable.toArray().filter(a => a.startsWith(prefix))
    // console.log(order)
    $.ajax({
        method: "POST",
        url,
        data: { action: "changeOrder", order, },
        success: function (response) {
            // toastr.success(response.message)
        },
        error: onKanbanAjaxError,
    })
    // console.log("setValue", order, prefix)
}

const addANewKanbanObj = (prefix, parent_id, url, groupWidth) => {
    console.log(url, parent_id)
    $.ajax({
        method: "POST",
        url,
        data: { action: "addNew", parent_id, groupWidth },
        success: function (response) {
            // toastr.success(response.message)
            // console.log(response)
            const { renderer } = response.hits
            const parentId = prefix + parent_id
            // console.log(parentId, renderer)
            $("#" + parentId).append(renderer)
        },
        error: onKanbanAjaxError,
    })
}

const onClickToEdit = (id, lbl_type, txt_type) => {
    const lbl = "#" + lbl_type + "_" + id
    const txt = "#" + txt_type + "_" + id
    // console.log("Hide", lbl, "show", txt)
    $(lbl).hide()
    $(txt).show().focus()//.select()
}

const onClickToCommit = (id, lbl_type, txt_type, caption_type, url) => {
    const lbl = "#" + lbl_type + "_" + id
    const caption = "#" + caption_type + "_" + id
    const txt = "#" + txt_type + "_" + id
    // console.log("Show", lbl, "hide", txt)
    const newText = $(txt).val()

    $(txt).hide()
    $(caption).html(newText)
    $(lbl).show()

    $.ajax({
        method: "POST",
        url,
        data: { action: "changeName", newText, id },
        success: function (response) {
            // toastr.success(response.message)

        },
        error: onKanbanAjaxError,
    })
}

const getCharactersAfterLastUnderscore = (str) => {
    const lastUnderscoreIndex = str.lastIndexOf('_');
    return (lastUnderscoreIndex !== -1) ? str.substring(lastUnderscoreIndex + 1) : str;
}

const onEnd = (e, url, category) => {
    const { from, to, item } = e
    // console.log(e, url, category)
    if (from.id === to.id) return //<<Only change order, parent doesn't change
    // console.log(sortable[i].toArray())
    // console.log("ON END - To:", to.id, "itemId:", item.id, "Cat:", category)
    const itemId = getCharactersAfterLastUnderscore(item.id)
    const newParentId = getCharactersAfterLastUnderscore(to.id)
    $.ajax({
        method: "POST",
        url,
        data: { action: "changeParent", category, itemId, newParentId },
        success: function (response) {
            toastr.success(response.message)
            // console.log(response)
            const { meta } = response
            switch (meta.table) {
                case "kanban_tasks":
                    // console.log(meta.newParentId)
                    $("#taskParentId_" + meta.id).val(meta.newParentId)
                    $("#taskParentTimeCountingType_" + meta.id).val(meta.parentCountingType)
                    break;
            }
        },
        error: onKanbanAjaxError,
    })
}

const kanbanInit1 = (prefix, columns, route, category) => {
    for (let i = 0; i < columns.length; i++) {
        const itemName = prefix + columns[i]
        // console.log("Making kanban for", itemName)
        var el = document.getElementById(itemName);
        if (el === null) {
            console.error("EL", itemName, "is NULL")
            continue
        }
        Sortable.create(el, {
            animation: 150,
            group: prefix,
            store: {
                set: (s) => setValue(s, route, getChildPrefix(prefix)),
            },

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

const kanbanLoadPage = (pageId, url, groupWidth) => {
    const beginWith = "bucket_"
    $("#txtCurrentPage").val(pageId);
    $("#divKanbanPage").slideUp("slow");

    globalInterval.forEach((interval) => clearInterval(interval));
    globalInterval.length = 0;

    const ids = []
    $("#toc_group_1").children().each((a, db0) => {
        if (db0.id === '') return
        // console.log(db0.id)
        $("#" + db0.id).children().each((a, db1) => {
            // console.log(db1.id, beginWith)
            if (db1.id.startsWith(beginWith)) {
                if (db1.id === '') return
                // console.log(db1.id)
                $("#" + db1.id).children().each((a, db2) => {
                    if (db2.id === '') return
                    // console.log(db2.id)
                    ids.push(getCharactersAfterLastUnderscore(db2.id))
                })
            }
        })
    })
    // console.log(ids)

    for (let i = 0; i < ids.length; i++) {
        $("#iconOpen_" + ids[i]).hide()
        $("#iconClose_" + ids[i]).show()
    }
    $("#iconClose_" + pageId).hide()
    $("#iconOpen_" + pageId).show()

    $.ajax({
        method: 'POST',
        url,
        data: { action: "loadKanbanPage", pageId, groupWidth },
        success: function (response) {
            // toastr.success(response.message)
            const { renderer } = response.hits
            $("#divKanbanPage").html(renderer)
            $("#divKanbanPage").slideDown("slow");
        },
        error: onKanbanAjaxError,
    })
}

const renameCurrentPage = (pageId) => {
    const currentPage = $("#txtCurrentPage").val()
    const isSamePage = currentPage == pageId
    // console.log(currentPage, pageId, isSamePage)
    if (isSamePage) {
        $('#divPageCard').html($('#txt_toc_' + pageId).val())
    }
}

const kanbanLoadModalRenderer = (txtTypeId, divTypeBody, url) => {
    const id = $("#" + txtTypeId).val()
    console.log("Starting up #", id, url)
    $.ajax({
        method: "POST",
        url,
        data: { action: "editItemRenderProps", id, },
        success: function (response) {
            const { renderer } = response.hits
            $("#" + divTypeBody).html(renderer)
            //   console.log(response)
        },
        error: onKanbanAjaxError,
    })
}

const getItem = () => {
    const formDataArray = $("#frmKanbanItem").serializeArray()
    // console.log(formDataArray)
    const item = {}
    for (let i = 0; i < formDataArray.length; i++) {
        const fieldName = formDataArray[i].name
        const isMultiple = fieldName == 'getMonitors1()[]'
        if (isMultiple) {
            if (!item[fieldName]) item[fieldName] = []
            item[fieldName].push(formDataArray[i].value)
        } else {
            item[fieldName] = formDataArray[i].value
        }
    }
    // console.log(item)
    return item;
}

const kanbanUpdateItem = (txtTypeId, url, prefix, groupWidth) => {
    const id = $("#" + txtTypeId).val()
    // const formData = $("#frmKanbanItem").serialize()
    // console.log("Updating up #", id, url, formData, formDataArray)
    const item = getItem() //<< This can't go inside $.ajax
    $.ajax({
        method: "POST",
        url,
        data: { action: "updateItemRenderProps", id, ...item, groupWidth },
        success: function (response) {
            const { renderer } = response.hits
            const myId = prefix + id
            const myDiv = $("#" + myId)
            myDiv.replaceWith(renderer)
        },
        error: onKanbanAjaxError,
    })
}

const kanbanDeleteItem = (txtTypeId, url, prefix) => {
    const id = $("#" + txtTypeId).val()
    const item = getItem() //<< This can't go inside $.ajax
    Swal.fire({
        title: 'Are you sure to delete:',
        text: item.name,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Delete',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                method: "POST",
                url,
                data: { action: "deleteItemRenderProps", id },
                success: function (response) {
                    // console.log(response)
                    $("#" + prefix + id)
                        .removeClass("bg-white") //<< For task
                        .addClass("bg-red-400 rounded")
                        .fadeOut(1000)
                    console.log("Deleted", prefix + id)
                },
                error: onKanbanAjaxError,
            })
        }
    })
}