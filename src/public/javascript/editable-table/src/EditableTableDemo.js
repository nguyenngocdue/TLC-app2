import { DataSource } from "./DataSource"

const tableParams = {
    tableId: "table_",
    maxH: 1000,
    tableWidth: 100,

    tableName: 'prod_runs',
    tableFnName: 'getProdRuns',

    entityParentType: 'App\\Models\\Prod_sequence',
    entityParentId: 100041,

    header: "This is a table header description",

    showPaginationTop: true,
    topLeftControls: "topLeftControls",
    topCenterControls: "topCenterControls",
    topRightControls: "topRightControls",

    showPaginationBottom: true,
    bottomLeftControls: "bottomLeftControls",
    bottomCenterControls: "bottomCenterControls",
    bottomRightControls: "bottomRightControls",

    footer: "This is a table footer description",
}

export const EditableTableDemo = () => {
    let settings, params
    const columns = DataSource.UserColumns
    const dataSource = DataSource.Users
    const headerToolbar = DataSource.UserHeaderToolbar

    params = { columns, dataSource, headerToolbar }

    settings = DataSource.TableSettings.editableMode
    const tableParams1a = { ...tableParams, tableId: tableParams.tableId + "1a", }
    const table01a = EditableTable({ ...params, settings, tableParams: tableParams1a })

    const tableParams1b = { ...tableParams, tableId: tableParams.tableId + "1b", header: '', footer: '' }
    const table01b = EditableTable({ ...params, settings, tableParams: tableParams1b })

    const editableMode = `Editable Mode #${tableParams1a.tableId}${table01a}Editable Mode #${tableParams1b.tableId}${table01b}`

    settings = DataSource.TableSettings.printableMode
    const tableParams2a = { ...tableParams, tableId: tableParams.tableId + "2a", }
    const table02a = EditableTable({ ...params, settings, tableParams: tableParams2a })

    const tableParams2b = { ...tableParams, tableId: tableParams.tableId + "2b", header: '', footer: '' }
    const table02b = EditableTable({ ...params, settings, tableParams: tableParams2b })

    const printableMode = `<div class="bg-gray-100 p-4 mb-2">Printable Mode #${tableParams2a.tableId}${table02a}</div>
    <div class="bg-gray-100 p-4 mb-2">Printable Mode #${tableParams2b.tableId}${table02b}</div>`

    const tables = `
    <div class="bg-white p-4">
        <div class="grid grid-cols-12 gap-4 m-4">
            <div class="xl:col-span-6 md:col-span-12">${editableMode}</div>
            <div class="xl:col-span-6 md:col-span-12">${printableMode}</div>
        </div>
    </div>
    `

    $("#divMain").html(tables)
}