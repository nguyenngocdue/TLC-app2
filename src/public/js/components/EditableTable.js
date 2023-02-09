const editableColumns = {}, tableObject = {}
const getAllRows = (tableId) => $("#" + tableId + " > tbody")[0].children
const getValueOfTrByName = (a, fieldName) => {
    let result = null
    a.childNodes.forEach((td) => {
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
const setValueOfTrByName = (a, fieldName, value) => {
    a.childNodes.forEach((td) => {
        td.childNodes.forEach((control) => {
            const name = control.name
            if (name !== undefined) {
                if (name.includes(fieldName))
                    control.value = value
            }
        })
    })
}
const getCellValueByName = (tableId, columnName, rowIndex) => {
    const rows = $("#" + tableId + " > tbody")[0].children
    // console.log(rows, tableId, columnName, rowIndex, rows[rowIndex])
    return getValueOfTrByName(rows[rowIndex], columnName)
}
const setCellValueByName = (tableId, columnName, rowIndex, value) => {
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
    // console.log("Re-render completed")
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
    const newFingerPrint = addANewLine({ tableId: control.value })

    // console.log("Duplicate", tableId, fingerPrint, newFingerPrint, columns)
    for (let i = 0; i < columns.length; i++) {
        const column = columns[i]
        if (['action', 'id', 'order_no'].includes(column.dataIndex)) continue
        const sourceRowIndex = getIndexFromFingerPrint(tableId, fingerPrint)
        const value = getCellValueByName(tableId, column['dataIndex'], sourceRowIndex)
        // console.log("Cloning", column['dataIndex'], "to", value)
        const targetRowIndex = getIndexFromFingerPrint(tableId, newFingerPrint)
        setCellValueByName(tableId, column['dataIndex'], targetRowIndex, value)
    }
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
    const { tableId } = params
    const { columns, showNo, showNoR, tableDebug } = tableObject[tableId]
    // console.log("ADD LINE TO", params, tableDebug)
    const table = document.getElementById(tableId)
    const row = table.insertRow()
    row.classList.add('bg-lime-200')
    let fingerPrint = ''
    if (showNo) { //<< Ignore No. column
        const noCell = row.insertCell()
        noCell.classList = "px-1 py-1 dark:border-gray-600 border-r text-center";
        noCell.innerHTML = "New"
    }
    columns.forEach((column) => {
        let renderer = 'newCell'

        const name = tableId + "[" + column['dataIndex'] + "][]"
        if (column['dataIndex'] === 'action') {
            fingerPrint = getMaxValueOfAColumn(tableId, "[finger_print]") + 10
            const params = "{tableId: '" + tableId + "', control:this, fingerPrint: " + fingerPrint + "}"

            const fingerPrintName = tableId + "[finger_print][]"
            const fingerPrintInput = '<input readonly class="w-10 bg-gray-300" name="' + fingerPrintName + '" value="' + fingerPrint + '" type=' + (tableDebug ? "text" : "hidden") + ' />'

            const destroyName = tableId + "[DESTROY_THIS_LINE][]"
            const destroyInput = '<input readonly class="w-10 bg-gray-300" name="' + destroyName + '" type=' + (tableDebug ? "text" : "hidden") + ' />'

            const btnUp = '<button value="' + tableId + '" onClick="moveUpEditableTable(' + params + ')" type="button" class="px-1.5 py-1  inline-block font-medium text-xs leading-tight uppercase rounded focus:ring-0 transition duration-150 ease-in-out bg-gray-200 text-gray-700 shadow-md hover:bg-gray-300 hover:shadow-lg focus:bg-gray-300 focus:shadow-lg focus:outline-none active:bg-gray-400 active:shadow-lg" ><i class="fa fa-arrow-up"></i></button>'
            const btnDown = '<button value="' + tableId + '" onClick="moveDownEditableTable(' + params + ')" type="button" class="px-1.5 py-1  inline-block font-medium text-xs leading-tight uppercase rounded focus:ring-0 transition duration-150 ease-in-out bg-gray-200 text-gray-700 shadow-md hover:bg-gray-300 hover:shadow-lg focus:bg-gray-300 focus:shadow-lg focus:outline-none active:bg-gray-400 active:shadow-lg" ><i class="fa fa-arrow-down"></i></button>'
            const btnDuplicate = '<button value = "' + tableId + '" onClick = "duplicateEditableTable(' + params + ')" type = "button" class="px-1.5 py-1  inline-block font-medium text-xs leading-tight uppercase rounded focus:ring-0 transition duration-150 ease-in-out bg-blue-600 text-white shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none active:bg-blue-800 active:shadow-lg"> <i class="fa fa-copy"></i></button >'
            const btnTrash = '<button value="' + tableId + '" onClick="trashEditableTable(' + params + ')" type="button" class="px-1.5 py-1  inline-block font-medium text-xs leading-tight uppercase rounded focus:ring-0 transition duration-150 ease-in-out bg-red-600 text-white shadow-md hover:bg-red-700 hover:shadow-lg focus:bg-red-700 focus:shadow-lg focus:outline-none active:bg-red-800 active:shadow-lg" ><i class="fa fa-trash"></i></button>'
            renderer = ""
                + fingerPrintInput
                + destroyInput
                + '<div class="whitespace-nowrap">'
                + btnUp
                + btnDown
                + btnDuplicate
                + btnTrash
                + '</div>'
        } else {
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
                        renderer = "<select name='" + name + "' class='" + column['classList'] + "'></select>"
                    }
                    break
                case "number":
                    let value = '', onChange = ''
                    if (column['dataIndex'] === 'order_no') {
                        value = getMaxValueOfAColumn(tableId, "[order_no]") + 10
                        onChange = "rerenderTableBaseOnNewOrder(\"" + tableId + "\")"
                    }
                    renderer = "<input name='" + name + "' class='" + column['classList'] + "' type=number step=any value='" + value + "' onChange='" + onChange + "' />";
                    break
                case "text":
                    renderer = "<input name='" + name + "' class='" + column['classList'] + "' />";
                    break
                case "textarea":
                    renderer = "<textarea name='" + name + "' class='" + column['classList'] + "'></textarea>"
                    break
                default:
                    renderer = "Unknown how to render " + column['renderer']
                    break
            }
        }
        //row.insertCell MUST run after get Max finger Print
        const cell = row.insertCell();
        cell.classList = "px-1 py-1 dark:border-gray-600 border-r text-center";
        // console.log("Insert column", column['dataIndex'], renderer)
        cell.innerHTML = renderer;
    })
    if (showNoR) { //<< Ignore No. column
        const noCell = row.insertCell()
        noCell.classList = "px-1 py-1 dark:border-gray-600 border-r text-center";
        noCell.innerHTML = "New"
    }
    return fingerPrint
}