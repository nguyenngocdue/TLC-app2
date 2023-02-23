const debugEditable = false
const editableColumns = {}, tableObject = {}, entityId = null
const getNameIndexOfRowIndex = (tableId, rowIndex) => {
    const rows = $("#" + tableId + " > tbody")[0].children
    const row = rows[rowIndex]
    for (let i = 0; i < row.childNodes.length; i++) {
        const td = row.childNodes[i]
        const tdName = td.childNodes[0].data
        // console.log(tdName, tdName.startsWith(tableId))
        if (tdName.startsWith(tableId + "[action]")) {
            return tdName.substring(tableId.length + "[action][".length, tdName.length - 1)
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
    // const debugEditable = true
    if (debugEditable) console.log("Moving up editable table", params)
    const { control, fingerPrint } = params
    const tableId = control.value
    const firstRowFingerPrintValue = 1 * getCellValueByName(tableId, '[finger_print]', 0)
    if (debugEditable) console.log(tableId, fingerPrint, firstRowFingerPrintValue)
    if (fingerPrint === firstRowFingerPrintValue) {
        const max = getMaxValueOfAColumn(tableId, "[order_no]")
        if (debugEditable) console.log("FIRST ROW, max of order_no", max)
        setCellValueByName(tableId, '[order_no]', 0, max + 10)
    } else {
        if (debugEditable) console.log("NORMAL ROW")
        const myRowIndex = getIndexFromFingerPrint(tableId, fingerPrint)
        const myValue = 1 * getCellValueByName(tableId, '[order_no]', myRowIndex)

        const previousRowIndex = myRowIndex - 1
        const myPreviousValue = 1 * getCellValueByName(tableId, '[order_no]', previousRowIndex)
        if (debugEditable) console.log(previousRowIndex, myRowIndex, myPreviousValue, myValue)

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
    const { control, fingerPrint, nameIndex } = params
    const tableId = control.value
    const { columns } = tableObject[tableId]

    const valuesOfOrigin = {}
    // console.log("Duplicate", tableId, fingerPrint, newFingerPrint, columns)
    for (let i = 0; i < columns.length; i++) {
        const column = columns[i]
        //Do not duplicate those columns
        if (['action', 'id', 'order_no'].includes(column.dataIndex)) continue
        const name = tableId + "[" + column['dataIndex'] + "][" + nameIndex + "]"
        const value = getValueById(name)
        if (column['renderer'] === 'toggle') {
            valuesOfOrigin[column['dataIndex']] = getEById(name)[0].checked
        } else {
            valuesOfOrigin[column['dataIndex']] = value
        }
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

const cloneFirstLineDown = (dataIndex, tableId) => {
    // const debugEditable = true
    const nameIndex = getNameIndexOfRowIndex(tableId, 0)
    if (debugEditable) console.log(nameIndex)
    const name = tableId + "[" + dataIndex + "][" + nameIndex + "]"
    const value = getValueById(name)
    // const value = getCellValueByName(tableId, '[' + dataIndex + ']', 0)
    if (debugEditable) console.log(tableId, dataIndex, '=', value)
    const length = getAllRows(tableId).length
    for (let i = 0; i < length; i++) {
        const id = makeIdFrom(tableId, dataIndex, i)
        getEById(id).val(value)
        getEById(id).trigger('change')
    }
}