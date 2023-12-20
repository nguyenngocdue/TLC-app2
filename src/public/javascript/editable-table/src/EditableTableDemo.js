import { DataSource } from "./DataSource"

const tableParams = {
    id: "tableId",
    maxH: 1000,
    tableWidth: 100,

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
    const tableParams1 = { ...tableParams }
    const table01 = EditableTable({ ...params, settings, tableParams: tableParams1 })

    const tableParams2 = { ...tableParams, header: '', footer: '' }
    const table02 = EditableTable({ ...params, settings, tableParams: tableParams2 })

    const editableMode = `
        Editable Mode
        ${table01}
        Editable Mode
        ${table02}
        `

    settings = DataSource.TableSettings.printableMode
    const printableMode = `
    <div class="bg-gray-100 p-4 mb-2">    
        Printable Mode
        ${EditableTable({ ...params, settings, tableParams })}
    </div>
    <div class="bg-gray-100 p-4 mb-2">
        Printable Mode
        ${EditableTable({ ...params, settings, tableParams })}
    </div>
    `

    const tables = `
    <div class="bg-white p-4">
        <div class="grid grid-cols-12 gap-4 m-4">
            <div class="xl:col-span-6 md:col-span-12">
                ${editableMode}
            </div>
            <div class="xl:col-span-6 md:col-span-12">
                ${printableMode}
            </div>
        </div>
    </div>
    `

    $("#divMain").html(tables)
}