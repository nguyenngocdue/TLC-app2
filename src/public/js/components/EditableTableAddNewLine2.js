const removeEmptinessLine = (tableId) => $('#' + tableId + '_emptiness').remove()

const findParentIdFieldName = (tableId) => {
    const { columns } = tableObject[tableId]
    for (let i = 0; i < columns.length; i++) {
        const keys = Object.keys(columns[i])
        // console.log(keys)
        for (let j = 0; j < keys.length; j++) {
            const key = keys[j]
            if (key === 'value_as_parent_id' && columns[i][key]) {
                // console.log(key, columns[i][key], columns[i]['dataIndex'])
                return columns[i]['dataIndex']
            }
        }
    }
    return "NOT_FOUND_value_as_parent_id";
}

const ajaxAddLineQueue = {}

const addANewLine = (params) => {
    const { tableId, valuesOfOrigin = {}, isDuplicatedOrAddFromList = false, batchLength = 1 } = params
    const { tableName, } = tableObject[tableId]
    // console.log(params, tableName,)
    const parentId = getEById('entityParentId').val()
    const orderNoValue = getMaxValueOfAColumn(tableId, "[order_no]") + 10
    const parentIdFieldName = findParentIdFieldName(tableId)
    // console.log(parentIdFieldName)
    const data0 = {
        owner_id: 1,
        [parentIdFieldName]: parentId,
        order_no: orderNoValue,

        ...valuesOfOrigin,
    }
    // console.log(data)

    const btnId = "btnAddANewLine_" + tableId
    const spinId = "iconSpin_" + tableId
    getEById(btnId).hide()
    getEById(spinId).show()

    const url = '/api/v1/entity/' + tableName + '_storeEmpty'

    if (ajaxAddLineQueue[url] == undefined) ajaxAddLineQueue[url] = {}
    if (ajaxAddLineQueue[url]['data'] == undefined) ajaxAddLineQueue[url]['data'] = []
    // if (ajaxAddLineQueue[url]['rowIndex'] == undefined) ajaxAddLineQueue[url]['rowIndex'] = []
    ajaxAddLineQueue[url]['data'].push(data0)
    // ajaxAddLineQueue[url]['rowIndex'].push(rowIndex)

    if (ajaxAddLineQueue[url]['data'].length >= batchLength) {
        const data = ajaxAddLineQueue[url]['data']

        delete (ajaxAddLineQueue[url])
        // console.log("Inserting", data)
        $.ajax({
            type: 'POST',
            url,
            data: { lines: data },
            success: (response) => {
                // console.log(response)
                for (let i = 0; i < batchLength; i++) {
                    const valuesOfOrigin = response['hits'][i]
                    // valuesOfOrigin['id'] = line.id
                    valuesOfOrigin['ot_date'] = moment(valuesOfOrigin['ot_date']).format('DD/MM/YYYY')
                    // console.log(valuesOfOrigin)
                    addANewLineFull({ tableId, valuesOfOrigin, isDuplicatedOrAddFromList, batchLength })
                }
                getEById(btnId).show()
                getEById(spinId).hide()
            }
        })
    }
}

const addANewLineFull = (params) => {

    const { tableId, isDuplicatedOrAddFromList, batchLength = 1 } = params
    let { valuesOfOrigin } = params //<< Incase of duplicate, this is the value of the original line
    const insertedId = valuesOfOrigin['id']
    // console.log('addANewLine', tableId, insertedId)
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
    const toDoAfterAddedDropdown4 = []
    const toDoAfterAddedDropdown4ReadOnly = []
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
        // console.log(column['dataIndex'], column['saveOnChange'])
        const saveOnChange = column['saveOnChange'] ? 1 : 0

        const id = tableId + "[" + column['dataIndex'] + "][" + newRowIndex + "]"
        if (column['dataIndex'] === 'action') {
            fingerPrint = getMaxValueOfAColumn(tableId, "[finger_print]") + 10

            const fingerPrintName = tableId + "[finger_print][" + newRowIndex + "]"
            const fingerPrintInput = '<input readonly class="w-10 bg-gray-300" name="' + fingerPrintName + '" value="' + fingerPrint + '" type=' + (tableDebugJs ? "text" : "hidden") + ' />'

            const destroyName = tableId + "[DESTROY_THIS_LINE][" + newRowIndex + "]"
            const destroyInput = '<input readonly class="w-10 bg-gray-300" name="' + destroyName + '" type=' + (tableDebugJs ? "text" : "hidden") + ' />'

            const paramString = "{tableId: '" + tableId + "', control: this, fingerPrint:" + fingerPrint + ", nameIndex:" + newRowIndex + "}"

            const btnUp = '<button value="' + tableId + '" onClick="moveUpEditableTable(' + paramString + ')" type="button" class="px-1.5 py-1  inline-block font-medium text-xs leading-tight uppercase rounded focus:ring-0 transition duration-150 ease-in-out bg-gray-200 text-gray-700 shadow-md hover:bg-gray-300 hover:shadow-lg focus:bg-gray-300 focus:shadow-lg focus:outline-none active:bg-gray-400 active:shadow-lg" ><i class="fa fa-arrow-up"></i></button>'
            const btnDown = '<button value="' + tableId + '" onClick="moveDownEditableTable(' + paramString + ')" type="button" class="px-1.5 py-1  inline-block font-medium text-xs leading-tight uppercase rounded focus:ring-0 transition duration-150 ease-in-out bg-gray-200 text-gray-700 shadow-md hover:bg-gray-300 hover:shadow-lg focus:bg-gray-300 focus:shadow-lg focus:outline-none active:bg-gray-400 active:shadow-lg" ><i class="fa fa-arrow-down"></i></button>'
            const btnDuplicate = '<button value = "' + tableId + '" onClick = "duplicateEditableTable(' + paramString + ')" type = "button" class="px-1.5 py-1  inline-block font-medium text-xs leading-tight uppercase rounded focus:ring-0 transition duration-150 ease-in-out bg-blue-600 text-white shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none active:bg-blue-800 active:shadow-lg"> <i class="fa fa-copy"></i></button >'
            const btnTrash = '<button value="' + tableId + '" onClick="trashEditableTable(' + paramString + ')" type="button" class="px-1.5 py-1  inline-block font-medium text-xs leading-tight uppercase rounded focus:ring-0 transition duration-150 ease-in-out bg-red-600 text-white shadow-md hover:bg-red-700 hover:shadow-lg focus:bg-red-700 focus:shadow-lg focus:outline-none active:bg-red-800 active:shadow-lg" ><i class="fa fa-trash"></i></button>'
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
            // console.log("Rendering", column)
            let onChangeParams = "{"
            onChangeParams += "name:\"" + id + "\","
            onChangeParams += "lineType:\"" + column['lineType'] + "\","
            onChangeParams += "table01Name:\"" + column['table01Name'] + "\","
            onChangeParams += "rowIndex: " + newRowIndex + ","
            onChangeParams += "saveOnChange: " + saveOnChange + ","
            onChangeParams += "batchLength " + ","
            onChangeParams += "}"

            const onChangeDropdown4Fn = "onChangeDropdown4(" + onChangeParams + ");"

            const makeOnChangeAdvanced = (moreCodeAfter) => ""
                + "$(\"[id='" + id + "']\").on('change', function(e, batchLength){"
                + moreCodeAfter
                + "})"

            switch (column['renderer']) {
                case 'read-only-text4':
                    if (column['dataIndex'] === 'id') {
                        if (insertedId) {
                            renderer = "<input id='" + id + "' name='" + id + "' value='" + insertedId + "' type='hidden' />"
                            renderer += "<div class='text-center'>" + insertedId + "</div>"
                            renderer += "<script>" + makeOnChangeAdvanced(onChangeDropdown4Fn) + "</script>"
                        } else {
                            renderer = "<input id='" + id + "' name='" + id + "' type='hidden' />"
                        }
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
                    multipleStr = column?.multiple ? "multiple" : ""
                    bracket = column?.multiple ? "[]" : ""
                    if (column['readOnly']) {
                        renderer = "<input id='" + id + "' name='" + id + bracket + "' type='hidden' >"
                        renderer += "<div id='" + id + "_label' class='px-2'></div>"
                    } else {
                        renderer = "<select id='" + id + "' name='" + id + bracket + "' " + multipleStr + "' class='" + column['classList'] + "'></select>"
                        renderer += "<script>getEById('" + id + "').select2({placeholder: 'Please select', templateResult: select2FormatState})</script>"
                    }
                    renderer += "<script>" + makeOnChangeAdvanced(onChangeDropdown4Fn) + "</script>"
                    break
                case "toggle4":
                    renderer = '<div class="flex justify-center">\
                    <label for="'+ id + '" class="inline-flex relative items-center cursor-pointer select">\
                        <input id="'+ id + '" name="' + id + '" type="checkbox" class="sr-only peer">\
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[\'\'] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>\
                    </label></div>'
                    break
                case "number4":
                    renderer = "<input id='" + id + "' name='" + id + "' " + (column['readOnly'] ? " readonly" : "") + " class='" + column['classList'] + "' type=number step=any />";
                    if (column['dataIndex'] === 'order_no') {
                        orderNoValue = getMaxValueOfAColumn(tableId, "[order_no]") + 10
                        const reRenderFn = "reRenderTableBaseOnNewOrder(\"" + tableId + "\")"
                        renderer += "<script>" + makeOnChangeAdvanced(reRenderFn) + "</script>"

                    } else {
                        // onChange = "onChangeDropdown4(" + onChangeParams + ");changeBgColor(this,\"" + tableId + "\")"
                        const changeBgColorFn = "changeBgColor(this,\"" + tableId + "\");"
                        renderer += "<script>" + makeOnChangeAdvanced(onChangeDropdown4Fn + changeBgColorFn) + "</script>"
                    }
                    break
                case "text4":
                    renderer = "<input id='" + id + "' name='" + id + "' " + (column['readOnly'] ? " readonly" : "") + " class='" + column['classList'] + "' />";
                    renderer += "<script>" + makeOnChangeAdvanced(onChangeDropdown4Fn) + "</script>"
                    break
                case "textarea4":
                    renderer = "<textarea id='" + id + "' name='" + id + "' " + (column['readOnly'] ? " readonly" : "") + " class='" + column['classList'] + "'></textarea>"
                    break
                case "picker-all4":
                    renderer = "<input id='" + id + "' name='" + id + "' placeholder='" + column['placeholder'] + "' class='" + column['classList'] + "'>"
                    renderer += "<script>" + makeOnChangeAdvanced(onChangeDropdown4Fn) + "</script>"
                    break
                default:
                    renderer = "Unknown how to render " + column['renderer']
                    break
            }
        }
        //row.insertCell MUST run after get Max finger Print
        const cell = row.insertCell();
        const hidden = column['invisible'] ? "hidden" : ""
        const cellId = "cell_" + id
        cell.classList = "dark:border-gray-600 border-r " + hidden;
        cell.id = cellId;
        // console.log("Insert column", column['dataIndex'], renderer)
        const showNameStr = tableDebugJs ? id : ""
        const cellHtml = showNameStr + renderer
        getEById(cellId).html(cellHtml) //<< This is to run <script>, cell.innerHTML doesn't run

        switch (column['renderer']) {
            case 'dropdown4':
                let selected
                if (isDuplicatedOrAddFromList) {
                    selected = valuesOfOrigin[column['dataIndex']]
                } else {
                    switch (true) {
                        case column['value_as_parent_id']: selected = $('#entityParentId').val(); break
                        case column['value_as_user_id']: selected = $('#currentUserId').val(); break
                    }
                }
                if (column['readOnly']) {
                    getEById(id).val(selected)
                    const dataSource = k[column['tableName']]
                    // console.log(dataSource)
                    let label = "Unknown #" + selected
                    for (let i = 0; i < dataSource.length; i++) {
                        if (dataSource[i].id == selected) {
                            label = dataSource[i].name
                            break
                        }
                    }
                    getEById(id + '_label').html(label)
                    toDoAfterAddedDropdown4ReadOnly.push({ id, dataSource: k[column['tableName']], tableId, selected })
                    // getEById(id).trigger('change')
                } else {
                    toDoAfterAddedDropdown4.push({ id, dataSource: k[column['tableName']], tableId, selected })
                }
                break
            case 'dropdown': //<<status
                let selected1 = valuesOfOrigin[column['dataIndex']]
                // console.log("Setting status", id, 'to', selected)
                getEById(id).val(selected1)
                getEById(id).trigger('change', batchLength)
                break
            default:
                if (column['value_as_parent_id']) {
                    getEById(id).val($('#entityParentId').val())
                    getEById(id).trigger('change', batchLength)
                    break
                }
                if (column['value_as_user_id']) {
                    getEById(id).val($('#userId').val())
                    getEById(id).trigger('change', batchLength)
                    break
                }
                if (column['dataIndex'] === 'order_no') {
                    getEById(id).val(orderNoValue)
                    getEById(id).trigger('change', batchLength)
                    break
                }
                const value = valuesOfOrigin[column['dataIndex']]
                if (column['renderer'] === 'toggle') {
                    getEById(id)[0].checked = value
                } else {
                    getEById(id).val(value)
                }
                getEById(id).trigger('change', batchLength)
                break

            // console.log("Added new column", column['dataIndex'])
        }
    })
    // console.log(toDoAfterAddedDropdown4)
    //This is to make sure the Listen assign will assign to the existing column
    for (let i = 0; i < toDoAfterAddedDropdown4.length; i++) {
        const { id, dataSource, tableId, selected } = toDoAfterAddedDropdown4[i]
        reloadDataToDropdown4(id, dataSource, tableId, selected)
        getEById(id).trigger('change', batchLength)
        // console.log("Triggered change", id)
    }
    for (let i = 0; i < toDoAfterAddedDropdown4ReadOnly.length; i++) {
        const { id, dataSource, tableId, selected } = toDoAfterAddedDropdown4ReadOnly[i]
        // reloadDataToDropdown4(id, dataSource, tableId, selected)
        getEById(id).trigger('change', batchLength)
        // console.log("Triggered change", id)
    }
    // console.log(showNoR)
    if (showNoR) { //<< Ignore No. column
        const noCell = row.insertCell()
        noCell.classList = "px-1 py-1 dark:border-gray-600 border-r text-center";
        noCell.innerHTML = "New"
    }
    return fingerPrint
}