import { DataSource } from "./DataSource"

const tableParams = {
    id: "tableId",
    maxH: 1000,
    tableWidth: 100,

    header: "This is a header",

    showPaginationTop: !false,
    topLeftControls: "topLeftControls",
    topCenterControls: "topCenterControls",
    topRightControls: "topRightControls",

    showPaginationBottom: !false,
    bottomLeftControls: "bottomLeftControls",
    bottomCenterControls: "bottomCenterControls",
    bottomRightControls: "bottomRightControls",

    footer: "This is a footer",
}

export const EditableTableDemo = () => {
    let settings
    const columns = DataSource.UserColumns
    const dataSource = DataSource.Users

    settings = DataSource.TableSettings.editableMode
    const editableMode = `
        Editable Mode
        ${EditableTable({ columns, dataSource, settings, tableParams })}
        Editable Mode
        ${EditableTable({ columns, dataSource, settings, tableParams })}
    `

    settings = DataSource.TableSettings.printMode
    const printableMode = `
        Printable Mode
        ${EditableTable({ columns, dataSource, settings, tableParams })}
        Printable Mode
        ${EditableTable({ columns, dataSource, settings, tableParams })}
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