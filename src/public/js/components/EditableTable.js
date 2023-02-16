const editableColumns = {}, tableObject = {}, entityId = null
const getAllRows = (tableId) => $("#" + tableId + " > tbody")[0].children
const getValueOfTrByName = (aRow, fieldName) => {
    let result = null
    aRow.childNodes.forEach((td) => {
        // console.log(td.firstChild)
        td.childNodes.forEach((control) => {
            const name = control.name
            if (name !== undefined) {
                if (name.includes(fieldName)) {
                    // console.log("Found name", name)
                    result = control.value
                }
            }
        })
    })
    // if (result === null) console.warn("No value found for field", fieldName)
    return result
}
const setValueOfTrByName = (aRow, fieldName, value) => {
    aRow.childNodes.forEach((td) => {
        td.childNodes.forEach((control) => {
            const name = control.name
            // console.log(name)
            if (name !== undefined) {
                if (name.includes(fieldName)) {
                    control.value = value
                    // console.log(control)
                }
            }
        })
    })
}
const getValueById = (id) => getEById(id).val()
const setValueById = (id, value) => getEById(id).val(value)

const getCellValueByName = (tableId, columnName, rowIndex) => {
    const rows = $("#" + tableId + " > tbody")[0].children
    // console.log(rows, tableId, columnName, rowIndex, rows[rowIndex])
    return getValueOfTrByName(rows[rowIndex], columnName)
}
const setCellValueByName = (tableId, columnName, rowIndex, value) => {
    // console.log("setCellValueByName", columnName, "to", value)
    const rows = $("#" + tableId + " > tbody")[0].children
    setValueOfTrByName(rows[rowIndex], columnName, value)
}

const rerenderTableBaseOnNewOrder = (tableId) => {
    const rows = getAllRows(tableId)
    const newTable = []
    for (let item of rows) newTable.push(item)
    const sortedTable = newTable.sort((a, b) => {
        const aValue = getValueOfTrByName(a, '[order_no]') * 1
        const bValue = getValueOfTrByName(b, '[order_no]') * 1
        return aValue - bValue
    })

    $("#" + tableId + ' > tbody').html(sortedTable)
    // console.log("Re-render completed", tableId, sortedTable)
}

const getIndexFromFingerPrint = (tableId, fingerPrint) => {
    const rows = getAllRows(tableId)
    for (let i = 0; i < rows.length; i++) {
        // console.log(rows.length, i)
        const cell = 1 * getCellValueByName(tableId, '[finger_print]', i)
        // console.log(cell, fingerPrint)
        if (cell === fingerPrint) return i
    }
    console.error("Can not find FingerPrint #", fingerPrint, "in all rows")
    return -1
}

const getMaxValueOfAColumn = (tableId, columnName) => {
    const rows = getAllRows(tableId)
    let max = Number.MIN_VALUE
    for (let i = 0; i < rows.length; i++) {
        const cell = 1 * getCellValueByName(tableId, columnName, i)
        // console.log(cell, max)
        if (cell > max) max = cell
    }
    // console.log("Max", max)
    return max
}

const getMinValueOfAColumn = (tableId, columnName) => {
    const rows = getAllRows(tableId)
    let min = Number.MAX_VALUE
    for (let i = 0; i < rows.length; i++) {
        const cell = 1 * getCellValueByName(tableId, columnName, i)
        // console.log(cell)
        if (cell < min) min = cell
    }
    // console.log("Min", min)
    return min
}

const moveUpEditableTable = (params) => {
    // console.log("Moving up editable table", params)
    const { control, fingerPrint } = params
    const tableId = control.value
    const firstRowFingerPrintValue = 1 * getCellValueByName(tableId, '[finger_print]', 0)
    // console.log(tableId, fingerPrint, firstRowFingerPrintValue)
    if (fingerPrint === firstRowFingerPrintValue) {
        const max = getMaxValueOfAColumn(tableId, "[order_no]")
        // console.log("FIRST ROW, max of order_no", max)
        setCellValueByName(tableId, '[order_no]', 0, max + 10)
    } else {
        // console.log("NORMAL ROW")
        const myRowIndex = getIndexFromFingerPrint(tableId, fingerPrint)
        const myValue = 1 * getCellValueByName(tableId, '[order_no]', myRowIndex)

        const previousRowIndex = myRowIndex - 1
        const myPreviousValue = 1 * getCellValueByName(tableId, '[order_no]', previousRowIndex)
        // console.log(previousRowIndex, myRowIndex, myPreviousValue, myValue)

        const tmp = myPreviousValue
        setCellValueByName(tableId, '[order_no]', previousRowIndex, myValue)
        setCellValueByName(tableId, '[order_no]', myRowIndex, tmp)
    }
    rerenderTableBaseOnNewOrder(tableId)
}

const moveDownEditableTable = (params) => {
    const { control, fingerPrint } = params
    const tableId = control.value
    const length = getAllRows(tableId).length
    const lastRowFingerPrintValue = 1 * getCellValueByName(tableId, '[finger_print]', length - 1)
    // console.log(tableId, fingerPrint, lastRowFingerPrintValue)
    if (fingerPrint === lastRowFingerPrintValue) {
        const min = getMinValueOfAColumn(tableId, "[order_no]")
        // console.log("FIRST ROW, min of order_by", min)
        setCellValueByName(tableId, '[order_no]', length - 1, min - 10)
    } else {
        // console.log("NORMAL ROW")
        const myRowIndex = getIndexFromFingerPrint(tableId, fingerPrint)
        const myValue = 1 * getCellValueByName(tableId, '[order_no]', myRowIndex)

        const nextRowIndex = myRowIndex + 1
        const myNextValue = 1 * getCellValueByName(tableId, '[order_no]', nextRowIndex)
        // console.log(nextRowIndex, myRowIndex)

        const tmp = myNextValue
        setCellValueByName(tableId, '[order_no]', nextRowIndex, myValue)
        setCellValueByName(tableId, '[order_no]', myRowIndex, tmp)
    }
    rerenderTableBaseOnNewOrder(tableId)
}

const duplicateEditableTable = (params) => {
    const { control, fingerPrint } = params
    const tableId = control.value
    const { columns } = tableObject[tableId]

    const valuesOfOrigin = {}
    // console.log("Duplicate", tableId, fingerPrint, newFingerPrint, columns)
    for (let i = 0; i < columns.length; i++) {
        const column = columns[i]
        const { multiple } = column
        //Do not duplicate those columns
        if (['action', 'id', 'order_no'].includes(column.dataIndex)) continue
        const sourceRowIndex = getIndexFromFingerPrint(tableId, fingerPrint)
        const value = getValueById(tableId + "[" + column['dataIndex'] + "][" + sourceRowIndex + "]")
        const valueStr = (Array.isArray(value)) ? value.join(",") : value
        valuesOfOrigin[column['dataIndex']] = multiple ? "[" + valueStr + "]" : valueStr
    }
    // console.log(valuesOfOrigin)
    addANewLine({ tableId: control.value, valuesOfOrigin })
}

const trashEditableTable = (params) => {
    const { control: button, fingerPrint } = params
    const tableId = button.value
    // console.log("Trash", tableId, fingerPrint)
    //This can be changed if the GUI changed
    const divWhiteSpace = button.parentNode
    const td = divWhiteSpace.parentNode
    const tr = td.parentNode

    const rowIndex = getIndexFromFingerPrint(tableId, fingerPrint)
    const value = getCellValueByName(tableId, '[DESTROY_THIS_LINE]', rowIndex) === 'true'
    const isDeleted = !value;
    setCellValueByName(tableId, '[DESTROY_THIS_LINE]', rowIndex, isDeleted)
    // console.log(button.firstChild)
    if (isDeleted) {
        tr.classList.add('bg-pink-400')
        button.firstChild.classList.remove('fa-trash')
        button.firstChild.classList.add('fa-trash-undo')
    } else {
        tr.classList.remove('bg-pink-400')
        button.firstChild.classList.add('fa-trash')
        button.firstChild.classList.remove('fa-trash-undo')
    }
}
const addANewLine = (params) => {
    const { tableId, } = params
    let { valuesOfOrigin } = params //<< Incase of duplicate, this is the value of the original line
    // console.log("valuesOfOrigin: ", valuesOfOrigin)
    const { columns, showNo, showNoR, tableDebugJs } = tableObject[tableId]
    // console.log("ADD LINE TO", params, tableDebugJs)
    const table = document.getElementById(tableId)
    const newRowIndex = getAllRows(tableId).length
    const row = table.insertRow()
    row.classList.add('bg-lime-200')
    let fingerPrint = ''
    if (showNo) { //<< Ignore No. column
        const noCell = row.insertCell()
        noCell.classList = "px-1 py-1 dark:border-gray-600 border-r text-center";
        noCell.innerHTML = "New"
    }
    columns.forEach((column) => {
        if (column['hidden'] == true) return
        let renderer = 'newCell'

        const multipleBracket = column?.multiple ? "[]" : ""
        const name = tableId + "[" + column['dataIndex'] + "][" + newRowIndex + "]" + multipleBracket
        if (column['dataIndex'] === 'action') {
            fingerPrint = getMaxValueOfAColumn(tableId, "[finger_print]") + 10
            const params = "{tableId: '" + tableId + "', control:this, fingerPrint: " + fingerPrint + "}"

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
                + '<div class="whitespace-nowrap flex">'
                + btnUp
                + btnDown
                + btnDuplicate
                + btnTrash
                + '</div>'
        } else {
            let onChange = ''
            let value = ''
            // console.log("Rendering", column)
            switch (column['renderer']) {
                case 'read-only-text':
                    if (column['dataIndex'] === 'id') {
                        renderer = "<input name=" + name + " type='hidden' />"
                    } else {
                        renderer = 'New read-only-text'
                    }
                    break
                case 'dropdown':
                    if (column['dataIndex'] === 'status') {
                        renderer = "<select name='" + name + "' class='" + column['classList'] + "'>"
                        column['cbbDataSource'].forEach((status) => {
                            statusObject = column['cbbDataSourceObject'][status]
                            renderer += "<option value='" + status + "'>" + statusObject.title + "</option>"
                        })
                        renderer += "</select>"
                    } else {
                        renderer = "Only STATUS is implemented for dropdown1."
                    }
                    break
                case 'dropdown4':
                    // onChangeDropdown4("table02[prod_discipline_id][5]", "prod_discipline_1", "table02", 5)
                    onChange = "onChangeDropdown4(\"" + name + "\", \"" + column['lineType'] + "\", \"" + column['table01Name'] + "\", " + newRowIndex + ")"
                    multipleStr = column?.multiple ? "multiple" : ""
                    renderer = "<select id='" + name + "' name='" + name + "' " + multipleStr + " onChange='" + onChange + "' class='" + column['classList'] + "'></select>"
                    renderer += "<script>getEById('" + name + "').select2({placeholder: 'Please select', templateResult: select2FormatState})</script>"
                    break
                case "toggle":
                case "number":
                    if (column['dataIndex'] === 'order_no') {
                        value = getMaxValueOfAColumn(tableId, "[order_no]") + 10
                        onChange = "rerenderTableBaseOnNewOrder(\"" + tableId + "\")"
                    }
                    renderer = "<input id='" + name + "' name='" + name + "' class='" + column['classList'] + "' type=number step=any value='" + value + "' onChange='" + onChange + "' />";
                    break
                case "text":
                    renderer = "<input id='" + name + "' name='" + name + "' class='" + column['classList'] + "' />";
                    break
                case "textarea":
                    renderer = "<textarea id='" + name + "' name='" + name + "' class='" + column['classList'] + "'></textarea>"
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
        const showNameStr = tableDebugJs ? name : ""
        cell.innerHTML = showNameStr + renderer

        let selected = '', parentType = ''

        if (column['value_as_parent_id'] == true) {
            selected = $('#entityParentId').val()
            selectedStr = "[" + selected + "]"
            // console.log("Setting parent id for the new line", selectedStr)
        }
        if (column['value_as_parent_type'] == true) {
            parentType = $('#entityParentType').val()
            // console.log("Setting parent id for the new line", selectedStr)
            cell.firstChild.value = parentType
        }

        selectedStr = (valuesOfOrigin == undefined) ? "" : valuesOfOrigin[column['dataIndex']]
        if (column['renderer'] === 'dropdown4') {
            // console.log("reloading", selectedStr)
            reloadDataToDropdown4(name, k[column['table']], tableId, selectedStr)
        } else {
            if (column['value_as_parent_id']) {
                cell.firstChild.value = selected // or selectedStr ???
            } else {
                getEById(name).val(selectedStr)
            }
        }


        // console.log("Add new line >  column", column['dataIndex'], column)
    })
    // console.log(showNoR)
    if (showNoR) { //<< Ignore No. column
        const noCell = row.insertCell()
        noCell.classList = "px-1 py-1 dark:border-gray-600 border-r text-center";
        noCell.innerHTML = "New"
    }
    return fingerPrint
}