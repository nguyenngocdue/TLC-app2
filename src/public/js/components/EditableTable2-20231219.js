const table_order_no_step = 1;
const debugEditable = false
const editableColumns = {}, dateTimeControls = {}, tableObject = {}, tableObjectColName = {}, entityId = null
const getNameIndexOfRowIndex = (tableId, rowIndex) => {
    // const debugEditable = true
    const rows = $("#" + tableId + " > tbody")[0].children
    const row = rows[rowIndex]
    if (debugEditable) console.log("row", row)
    for (let i = 0; i < row.childNodes.length; i++) {
        const td = row.childNodes[i]
        for (let j = 0; j < td.childNodes.length; j++) {
            if (td.childNodes[j].name == undefined) continue
            if (debugEditable) console.log(td, td.childNodes[j], td.childNodes[j].name)
            const tdName = td.childNodes[j].name
            if (debugEditable) console.log(tdName, tdName.startsWith(tableId))
            if (tdName.startsWith(tableId + "[finger_print]")) {
                const result = tdName.substring(tableId.length + "[finger_print][".length, tdName.length - 1)
                if (debugEditable) console.log(result)
                return result
            }
        }
    }
    return -1
}
const getAllRows = (tableId) => $("#" + tableId + " > tbody")[0].children
const getValueOfTrByName = (aRow, fieldName) => {
    let result = null
    aRow.childNodes.forEach((td) => {
        // console.log(td.firstChild)
        td.childNodes.forEach((control) => {
            const name = control.name
            if (name !== undefined) {
                if (name.includes(fieldName)) {
                    result = control.value
                }
            }
        })
    })
    // if (result === null) console.warn("No value found for field", fieldName)
    return result
}
const setValueOfTrByName = (aRow, fieldName, value) => {
    fieldName = "[" + fieldName + "]"
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
const getValueById = (id, renderer) => (renderer === 'toggle') ? getEById(id)[0].checked : getEById(id).val()
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


const reRenderQueue = {}
const reRenderTableBaseOnNewOrder = (tableId, dropdownParams = {}) => {
    // const debugEditable = true
    const { batchLength = 1 } = dropdownParams
    if (debugEditable) console.log("reRenderTableBaseOnNewOrder", tableId, batchLength)
    if (reRenderQueue[tableId] === undefined) reRenderQueue[tableId] = 1
    if (reRenderQueue[tableId] < batchLength) {
        if (debugEditable) console.log("Enqueue and wait...")
        reRenderQueue[tableId]++;
    } else {
        delete reRenderQueue[tableId]

        var colIndex = -1;
        $("#" + tableId + " th").each(function (index) {
            if ($(this).attr("id") === tableId + "_th_" + "order_no") {
                colIndex = index;
                return false; // Exit the loop once found
            }
        });

        // Sort the table rows based on the values in the selected column
        var tbody = $('#' + tableId + ' tbody');
        var rows = tbody.children('tr').get();
        rows.sort(function (a, b) {
            var aVal = parseFloat($(a).children('td').eq(colIndex).find('input').val());
            var bVal = parseFloat($(b).children('td').eq(colIndex).find('input').val());
            return aVal - bVal;
        });

        // Clear the table body and re-append the sorted rows
        $.each(rows, function (index, row) {
            tbody.append(row);
        });
    }
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
    // const debugEditable = true
    if (debugEditable) console.log("Moving up editable table", params)
    const { control, fingerPrint } = params
    const tableId = control.value
    const firstRowFingerPrintValue = 1 * getCellValueByName(tableId, '[finger_print]', 0)
    if (debugEditable) console.log(tableId, fingerPrint, firstRowFingerPrintValue)
    if (fingerPrint === firstRowFingerPrintValue) {
        const max = getMaxValueOfAColumn(tableId, "[order_no]")
        // if (debugEditable) console.log("FIRST ROW, max of order_no", max)
        setCellValueByName(tableId, 'order_no', 0, max + table_order_no_step)
        if (debugEditable) console.log("First ROW, move up, make it to MAX=", max + table_order_no_step)
    } else {
        // if (debugEditable) console.log("NORMAL ROW")
        const myRowIndex = getIndexFromFingerPrint(tableId, fingerPrint)
        const myValue = 1 * getCellValueByName(tableId, '[order_no]', myRowIndex)

        const previousRowIndex = myRowIndex - 1
        const myPreviousValue = 1 * getCellValueByName(tableId, '[order_no]', previousRowIndex)
        if (debugEditable) console.log(previousRowIndex, myRowIndex, myPreviousValue, myValue)

        const tmp = myPreviousValue
        setCellValueByName(tableId, 'order_no', previousRowIndex, myValue)
        setCellValueByName(tableId, 'order_no', myRowIndex, tmp)
        if (debugEditable) console.log("Normal ROW, move up, swap [", previousRowIndex, "]=", myValue, "with [", myRowIndex, "]=", tmp)
    }
    reRenderTableBaseOnNewOrder(tableId)
}

const moveDownEditableTable = (params) => {
    // const debugEditable = true
    if (debugEditable) console.log("Moving down editable table", params)
    const { control, fingerPrint } = params
    const tableId = control.value
    const length = getAllRows(tableId).length
    const lastRowFingerPrintValue = 1 * getCellValueByName(tableId, '[finger_print]', length - 1)
    // console.log(tableId, fingerPrint, lastRowFingerPrintValue)
    if (fingerPrint === lastRowFingerPrintValue) {
        const min = getMinValueOfAColumn(tableId, "[order_no]")
        // console.log("FIRST ROW, min of order_by", min)
        setCellValueByName(tableId, 'order_no', length - 1, min - table_order_no_step)
        if (debugEditable) console.log("First ROW, move up, make it to MIN=", min - table_order_no_step)
    } else {
        // console.log("NORMAL ROW")
        const myRowIndex = getIndexFromFingerPrint(tableId, fingerPrint)
        const myValue = 1 * getCellValueByName(tableId, '[order_no]', myRowIndex)

        const nextRowIndex = myRowIndex + 1
        const myNextValue = 1 * getCellValueByName(tableId, '[order_no]', nextRowIndex)
        // console.log(nextRowIndex, myRowIndex)

        const tmp = myNextValue
        setCellValueByName(tableId, 'order_no', nextRowIndex, myValue)
        setCellValueByName(tableId, 'order_no', myRowIndex, tmp)
        if (debugEditable) console.log("Normal ROW, move up, swap [", nextRowIndex, "]=", myValue, "with [", myRowIndex, "]=", tmp)
    }
    reRenderTableBaseOnNewOrder(tableId)
}

const duplicateLineEditableTable = (params) => {
    const { control, fingerPrint, nameIndex } = params
    const tableId = control.value
    const { columns } = tableObject[tableId]

    const valuesOfOrigin = {}
    // console.log("Duplicate", tableId, fingerPrint, newFingerPrint, columns)
    for (let i = 0; i < columns.length; i++) {
        const column = columns[i]
        //Do not duplicate those columns
        if (['action', 'id', 'order_no'].includes(column.dataIndex)) continue
        let name = tableId + "[" + column['dataIndex'] + "][" + nameIndex + "]"
        // console.log(column, column['properties'])
        if (column['properties']?.['control'] == 'picker_datetime' || column['control'] == 'picker_datetime') {
            name = "hidden_" + name
            // console.log("Get from hidden filed", name)
        }
        const value = getValueById(name, column['renderer'])
        valuesOfOrigin[column['dataIndex']] = value
    }
    // console.log(valuesOfOrigin)
    addANewLine({ tableId: control.value, valuesOfOrigin, isDuplicatedOrAddFromList: true })
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
    setCellValueByName(tableId, 'DESTROY_THIS_LINE', rowIndex, isDeleted)
    const icon_i_tag = button.firstChild
    // console.log(button, icon_i_tag)
    if (isDeleted) {
        tr.classList.add('bg-pink-400')
        icon_i_tag.classList.remove('fa-trash')
        icon_i_tag.classList.add('fa-trash-undo')
    } else {
        tr.classList.remove('bg-pink-400')
        icon_i_tag.classList.add('fa-trash')
        icon_i_tag.classList.remove('fa-trash-undo')
    }
}

const refreshCalculation = (tableId) => {
    // const debugEditable = true
    const length = getAllRows(tableId).length
    if (debugEditable) console.log(tableId, "length", length)
    for (let i = 0; i < length; i++) {
        const id = makeIdFrom(tableId, 'id', i)
        getEById(id).trigger('change', { batchLength: length })
        if (debugEditable) console.log("Triggered change", id)
    }
}

const refreshCalculationRO = (tableId) => {
    const tableColumns = tableObjectColumns[tableId]//+ "RO"]
    // console.log(tableColumns, tableId)
    for (let i = 0; i < tableColumns.length; i++) {
        if (tableColumns[i]['footer']) {
            // console.log(tableColumns[i])
            const { dataIndex } = tableColumns[i]
            for (let j = 0; j < footerAggList.length; j++) {
                const { eloquentFn } = tableObject[tableId]
                const inputName = eloquentFn + '[footer][' + dataIndex + '][' + footerAggList[j] + ']'
                // console.log("trigger change for", inputName, dataIndex)
                getEById(inputName).trigger('change')
            }
        }
    }
}

const cloneFirstLineDown = (dataIndex, tableId, renderer) => {
    // const debugEditable = true
    if (debugEditable) console.log(tableId)
    const nameIndex = getNameIndexOfRowIndex(tableId, 0)
    if (debugEditable) console.log(nameIndex, renderer)
    const column = tableObjectIndexedColumns[tableId]
    const control = (column[dataIndex]['properties']['control'])
    const name = tableId + "[" + dataIndex + "][" + nameIndex + "]"
    const value = getValueById(name, renderer)
    // const value = getCellValueByName(tableId, '[' + dataIndex + ']', 0)
    if (debugEditable) console.log(tableId, dataIndex, '=', value)
    const length = getAllRows(tableId).length
    for (let i = 0; i < length; i++) {
        const id = makeIdFrom(tableId, dataIndex, i)

        if (renderer === 'toggle') {
            getEById(id)[0].checked = value
        } else if (control === 'picker_datetime') {
            newFlatPickrDateTime(id).setDate(value);
            getEById("hidden_" + id).val(value);
            getEById(id).trigger('change', { batchLength: length })
        } else if (control === 'picker_date') {
            newFlatPickrDate(id).setDate(value);
            getEById(id).trigger('change', { batchLength: length })
        }
        else if (control === 'picker_time') {
            newFlatPickrTime(id).setDate(value);
            getEById(id).trigger('change', { batchLength: length })
        } else {
            // console.log("Applying data for", id, value)
            getEById(id).val(value)
            getEById(id).trigger('change', { batchLength: length })
        }

        // if (renderer === 'toggle') {
        //     getEById(id)[0].checked = value
        //     // getEById(id).trigger('change')
        // } else {
        //     getEById(id).val(value)
        //     getEById(id).trigger('change', { batchLength: length })
        // }
    }
}