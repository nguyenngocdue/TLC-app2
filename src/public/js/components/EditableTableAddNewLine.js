const removeEmptinessLine = (tableId) => $('#' + tableId + '_emptiness').remove()
const addANewLine = (params) => {
    const { tableId } = params
    // console.log('addANewLine', tableId)
    let { valuesOfOrigin } = params //<< Incase of duplicate, this is the value of the original line
    // console.log("valuesOfOrigin: ", valuesOfOrigin)
    const { columns, showNo, showNoR, tableDebugJs, isOrderable } = tableObject[tableId]
    // console.log("ADD LINE TO", params, tableDebugJs, isOrderable)

    const table = document.getElementById(tableId)
    const row = table.insertRow()
    removeEmptinessLine(tableId) //<< Must remove after insertRow, otherwise it will insert into 2nd thead
    row.classList.add('bg-lime-200')
    let fingerPrint = ''
    if (showNo) { //<< Ignore No. column
        const noCell = row.insertCell()
        noCell.classList = "px-1 py-1 dark:border-gray-600 border-r text-center";
        noCell.innerHTML = "New"
    }
    const toDoAfterAdded = []
    const newRowIndex = getAllRows(tableId).length - 1 //exclude itself
    // console.log("newRowIndex", newRowIndex, getAllRows(tableId))
    columns.forEach((column) => {
        if (column['hidden'] == true) return
        let renderer = 'newCell'
        let orderNoValue = 0
        if (column['properties']) {
            Object.keys(column['properties']).forEach((key) => {
                column[key] = column['properties'][key]
            })
            delete column['properties']
            // console.log(column)
        }

        const id = tableId + "[" + column['dataIndex'] + "][" + newRowIndex + "]"
        if (column['dataIndex'] === 'action') {
            fingerPrint = getMaxValueOfAColumn(tableId, "[finger_print]") + 10

            const params = "{tableId: '" + tableId + "', control:this, fingerPrint: " + fingerPrint + ", nameIndex: " + newRowIndex + "}"

            const fingerPrintName = tableId + "[finger_print][" + newRowIndex + "]"
            const fingerPrintInput = '<input readonly class="w-10 bg-gray-300" name="' + fingerPrintName + '" value="' + fingerPrint + '" type=' + (tableDebugJs ? "text" : "hidden") + ' />'

            const destroyName = tableId + "[DESTROY_THIS_LINE][" + newRowIndex + "]"
            const destroyInput = '<input readonly class="w-10 bg-gray-300" name="' + destroyName + '" type=' + (tableDebugJs ? "text" : "hidden") + ' />'

            const btnUp = '<button value="' + tableId + '" onClick="moveUpEditableTable(' + params + ')" type="button" class="px-1.5 py-1  inline-block font-medium text-xs leading-tight uppercase rounded focus:ring-0 transition duration-150 ease-in-out bg-gray-200 text-gray-700 shadow-md hover:bg-gray-300 hover:shadow-lg focus:bg-gray-300 focus:shadow-lg focus:outline-none active:bg-gray-400 active:shadow-lg" ><i class="fa fa-arrow-up"></i></button>'
            const btnDown = '<button value="' + tableId + '" onClick="moveDownEditableTable(' + params + ')" type="button" class="px-1.5 py-1  inline-block font-medium text-xs leading-tight uppercase rounded focus:ring-0 transition duration-150 ease-in-out bg-gray-200 text-gray-700 shadow-md hover:bg-gray-300 hover:shadow-lg focus:bg-gray-300 focus:shadow-lg focus:outline-none active:bg-gray-400 active:shadow-lg" ><i class="fa fa-arrow-down"></i></button>'
            const btnDuplicate = '<button value = "' + tableId + '" onClick = "duplicateEditableTable(' + params + ')" type = "button" class="px-1.5 py-1  inline-block font-medium text-xs leading-tight uppercase rounded focus:ring-0 transition duration-150 ease-in-out bg-blue-600 text-white shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none active:bg-blue-800 active:shadow-lg"> <i class="fa fa-copy"></i></button >'
            const btnTrash = '<button value="' + tableId + '" onClick="trashEditableTable(' + params + ')" type="button" class="px-1.5 py-1  inline-block font-medium text-xs leading-tight uppercase rounded focus:ring-0 transition duration-150 ease-in-out bg-red-600 text-white shadow-md hover:bg-red-700 hover:shadow-lg focus:bg-red-700 focus:shadow-lg focus:outline-none active:bg-red-800 active:shadow-lg" ><i class="fa fa-trash"></i></button>'
            renderer = ""
                + fingerPrintInput
                + destroyInput
                + '<div class="whitespace-nowrap flex justify-center">'
                + (isOrderable ? btnUp : "")
                + (isOrderable ? btnDown : "")
                + btnDuplicate
                + btnTrash
                + '</div>'
        } else {
            let onChange = ''
            // console.log("Rendering", column)
            switch (column['renderer']) {
                case 'read-only-text':
                    if (column['dataIndex'] === 'id') {
                        renderer = "<input name=" + id + " type='hidden' />"
                    } else {
                        renderer = 'New read-only-text'
                    }
                    break
                case 'dropdown':
                    if (column['dataIndex'] === 'status') {
                        renderer = "<select id='" + id + "' name='" + id + "' class='" + column['classList'] + "'>"
                        column['cbbDataSource'].forEach((status) => {
                            statusObject = column['cbbDataSourceObject'][status]
                            renderer += "<option value='" + status + "'>" + statusObject.title + "</option>"
                        })
                        renderer += "</select>"
                    } else {
                        renderer = "Only STATUS has been implemented for dropdown1."
                    }
                    break
                case 'dropdown4':
                    // onChangeDropdown4({name:"table02[prod_discipline_id][5]",  table01Name:"table02", rowIndex:5, lineType:"prod_discipline_1"})
                    onChange = "onChangeDropdown4({name:\"" + id + "\", lineType:\"" + column['lineType'] + "\", table01Name:\"" + column['table01Name'] + "\", rowIndex: " + newRowIndex + "})"

                    multipleStr = column?.multiple ? "multiple" : ""
                    bracket = column?.multiple ? "[]" : ""
                    renderer = "<select id='" + id + "' name='" + id + bracket + "' " + multipleStr + " onChange='" + onChange + "' class='" + column['classList'] + "'></select>"
                    renderer += "<script>getEById('" + id + "').select2({placeholder: 'Please select', templateResult: select2FormatState})</script>"
                    break
                case "toggle":
                    renderer = '<div class="flex justify-center">\
                    <label for="'+ id + '" class="inline-flex relative items-center cursor-pointer select">\
                        <input id="'+ id + '" name="' + id + '" type="checkbox" class="sr-only peer">\
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[\'\'] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>\
                    </label></div>'
                    break
                case "number":
                    if (column['dataIndex'] === 'order_no') {
                        orderNoValue = getMaxValueOfAColumn(tableId, "[order_no]") + 10
                        onChange = "rerenderTableBaseOnNewOrder(\"" + tableId + "\")"
                    } else {
                        onChange = "onChangeDropdown4({name:\"" + id + "\", table01Name:\"" + column['table01Name'] + "\", rowIndex:" + newRowIndex + "})"
                    }
                    renderer = "<input id='" + id + "' name='" + id + "' class='" + column['classList'] + "' type=number step=any onChange='" + onChange + "' />";
                    break
                case "text":
                    // console.log(id, column, column['table01Name'])
                    onChange = "onChangeDropdown4({name:\"" + id + "\", table01Name:\"" + column['table01Name'] + "\", rowIndex:" + newRowIndex + "})"
                    renderer = "<input id='" + id + "' name='" + id + "' class='" + column['classList'] + "' onChange='" + onChange + "'/>";
                    break
                case "textarea":
                    renderer = "<textarea id='" + id + "' name='" + id + "' class='" + column['classList'] + "'></textarea>"
                    break
                case "picker-datetime4":
                    onChange = "onChangeDropdown4({name:\"" + id + "\", table01Name:\"" + column['table01Name'] + "\", rowIndex:" + newRowIndex + "})"
                    renderer = "<input id='" + id + "' name='" + id + "' placeholder='DD/MM/YYYY HH:MM' class='" + column['classList'] + "' onchange='" + onChange + "'>"
                    break
                case "picker-date4":
                    onChange = "onChangeDropdown4({name:\"" + id + "\", table01Name:\"" + column['table01Name'] + "\", rowIndex:" + newRowIndex + "})"
                    renderer = "<input id='" + id + "' name='" + id + "' placeholder='DD/MM/YYYY' class='" + column['classList'] + "' onchange='" + onChange + "'>"
                    break
                case "picker-time4":
                    onChange = "onChangeDropdown4({name:\"" + id + "\", table01Name:\"" + column['table01Name'] + "\", rowIndex:" + newRowIndex + "})"
                    renderer = "<input id='" + id + "' name='" + id + "' placeholder='HH:MM' class='" + column['classList'] + "' onchange='" + onChange + "'>"
                    break
                default:
                    renderer = "Unknown how to render " + column['renderer']
                    break
            }
        }
        //row.insertCell MUST run after get Max finger Print
        const cell = row.insertCell();
        const hidden = column['invisible'] ? "hidden" : ""
        cell.classList = "p1x-1 p1y-1 dark:border-gray-600 border-r text-center " + hidden;
        // console.log("Insert column", column['dataIndex'], renderer)
        const showNameStr = tableDebugJs ? id : ""
        cell.innerHTML = showNameStr + renderer

        switch (column['renderer']) {
            case 'dropdown4':
                let selected
                if (valuesOfOrigin == undefined) {
                    if (column['value_as_parent_id'] == true) selected = $('#entityParentId').val()
                    if (column['value_as_user_id'] == true) selected = $('#userId').val()
                } else {
                    selected = valuesOfOrigin[column['dataIndex']]
                }
                // console.log("reloading", valuesOfOrigin, selected)
                toDoAfterAdded.push({ id, dataSource: k[column['tableName']], tableId, selected })
                break
            case 'dropdown': //<<status
                if (valuesOfOrigin != undefined) {
                    let selected = valuesOfOrigin[column['dataIndex']]
                    console.log("Setting status", id, 'to', selected)
                    getEById(id).val('in_progress')
                }
                break
            default:
                if (column['value_as_parent_id']) {
                    getEById(id).val($('#entityParentId').val())
                    break
                }
                if (column['value_as_user_id']) {
                    getEById(id).val($('#userId').val())
                    break
                }
                if (column['dataIndex'] === 'order_no') {
                    getEById(id).val(orderNoValue)
                    break
                }
                if (valuesOfOrigin != undefined) {
                    const value = valuesOfOrigin[column['dataIndex']]
                    if (column['renderer'] === 'toggle') {
                        getEById(id)[0].checked = value
                    } else {
                        getEById(id).val(value)
                    }
                    break
                }

            // console.log("Added new column", column['dataIndex'])
        }
    })
    // console.log(toDoAfterAdded)
    //This is to make sure the Listen assign will assign to the existing column
    for (let i = 0; i < toDoAfterAdded.length; i++) {
        const { id, dataSource, tableId, selected } = toDoAfterAdded[i]
        reloadDataToDropdown4(id, dataSource, tableId, selected)
        getEById(id).trigger("change")
    }
    // console.log(showNoR)
    if (showNoR) { //<< Ignore No. column
        const noCell = row.insertCell()
        noCell.classList = "px-1 py-1 dark:border-gray-600 border-r text-center";
        noCell.innerHTML = "New"
    }
    return fingerPrint
}