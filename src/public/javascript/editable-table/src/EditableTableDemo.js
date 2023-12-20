import { DataSource } from "./DataSource"

const tableParams = {
    id: "tableId",
    maxH: 1000,
    tableWidth: 100,

    header: "This is a table header description",

    showPaginationTop: !false,
    topLeftControls: "topLeftControls",
    topCenterControls: "topCenterControls",
    topRightControls: "topRightControls",

    showPaginationBottom: !false,
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
    params = { columns, dataSource, tableParams, headerToolbar }

    settings = DataSource.TableSettings.editableMode
    const editableMode = `
        Editable Mode
        ${EditableTable({ ...params, settings })}
        Editable Mode
        ${EditableTable({ ...params, settings })}
        `

    settings = DataSource.TableSettings.printableMode
    const printableMode = `
        Printable Mode
        ${EditableTable({ ...params, settings })}
        Printable Mode
        ${EditableTable({ ...params, settings })}
    `

    const tables = `
        <div class="grid grid-cols-12 gap-4 m-4">
            <div class="col-span-6">
            ${editableMode}
            </div>
            <div class="col-span-6">
            ${printableMode}
            </div>
        </div>
    `

    $("#divMain").html(tables)
}