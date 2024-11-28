import { TableColumn, TableColumnCheckbox } from '../../Type/EditableTable3ColumnType'
import { LengthAware, TableCellType } from '../../Type/EditableTable3DataLineType'

declare let tableColumns: { [tableName: string]: TableColumn[] }
declare let tableData: { [tableName: string]: LengthAware }

export const renderMasterCB = (tableName: string, column: TableColumn) => {
    const tmpCol = column as TableColumnCheckbox
    if (!tmpCol.rendererAttrs?.hasMasterCheckbox) return ``
    return `<button
        id="${tableName}__${column.dataIndex}__master_checkbox" 
        class="hover:bg-gray-300 active:bg-gray-400 rounded cursor-pointer px-2 py-1"
        type="button"
        >
        <i id="${tableName}__${column.dataIndex}__master_checkbox_icon" class="fa fa-square-check"></i>
        </button>`
}

const masterCbState: { [tableName_dataIndex_renderer: string]: number } = {}
const storedCbValues: {
    [tableName_dataIndex_renderer: string]: { [checkboxId: string]: boolean }
} = {}

const backupCbValues = (tableName: string, dataIndex: string | number, renderer: string) => {
    const dataSource = tableData[tableName]
    const count = dataSource.data.length
    // console.log(dataSource.data, count)
    const key = `${tableName}__${dataIndex}__${renderer}`
    storedCbValues[key] = {}
    for (let rowIndex = 0; rowIndex < count; rowIndex++) {
        const cbId = `${tableName}__${dataIndex}__${renderer}__${rowIndex}`
        const cb = dataSource.data[rowIndex][dataIndex] as unknown as boolean

        storedCbValues[key][cbId] = cb
    }
    // console.log('storedCbValues', key, storedCbValues[key])
}

const onClickMasterCB = (tableName: string, column: TableColumnCheckbox) => {
    const dataSource = tableData[tableName]
    const rowCount = dataSource.data.length
    // console.log('rowCount', rowCount, dataSource.data)
    const { dataIndex, renderer } = column

    // Create a unique key to track state
    const key = `${tableName}__${dataIndex}__${renderer}`

    // Backup checkbox values if not already stored
    if (!masterCbState[key]) backupCbValues(tableName, dataIndex, renderer)

    // Initialize or cycle through the 3 states (1: on, 2: off, 0: current)
    masterCbState[key] = masterCbState[key] === undefined ? 1 : (masterCbState[key] + 1) % 3
    const masterCbId = `${tableName}__${dataIndex}__master_checkbox`

    let cb: HTMLInputElement | null = null
    for (let rowIndex = 0; rowIndex < rowCount; rowIndex++) {
        const cbId = `${tableName}__${dataIndex}__${renderer}__${rowIndex}`

        // Determine the checkbox value based on the state
        let value: boolean = false
        let iconClass: string = 'fa fa-square'
        switch (masterCbState[key]) {
            case 1: // On: set to true
                value = true
                iconClass = `fa fa-square`
                break
            case 2: // Off: set to false
                value = false
                iconClass = `fa fa-minus-square`
                break
            case 0: // Current: restore original value
                value = storedCbValues[key]?.[cbId] ?? false
                iconClass = `fa fa-check-square`
                break
        }

        // Update the data source and checkbox state
        dataSource.data[rowIndex][dataIndex] = value as unknown as TableCellType

        cb = document.getElementById(cbId) as HTMLInputElement
        if (cb) cb.checked = value
        // console.log(`update ${cbId} to ${value}`)

        // Update the checkbox icon
        const icon = document.getElementById(`${masterCbId}_icon`) as HTMLElement
        if (icon) icon.className = iconClass
    }

    //trigger change jQuery doesn't work here
    //trigger change for the last checkbox to show/hide the master button group
    if (cb) cb.dispatchEvent(new Event('change'))
}

export const registerOnClickMasterCB = (tableName: string) => {
    const columns = tableColumns[tableName]
    columns.forEach((column) => {
        const tmpCol = column as TableColumnCheckbox
        if (!tmpCol.rendererAttrs?.hasMasterCheckbox) return
        const masterId = `${tableName}__${column.dataIndex}__master_checkbox`
        const masterCB = document.getElementById(masterId)

        if (masterCB) masterCB.addEventListener('click', () => onClickMasterCB(tableName, tmpCol))
    })
}
