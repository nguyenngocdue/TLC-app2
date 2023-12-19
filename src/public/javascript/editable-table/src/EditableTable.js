import { EditableModeWrapper } from './TableMode/EditableModeWrapper'
import { PrintMode } from './TableMode/PrintMode'

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

export const EditableTable = (params) => {
    // console.log(params)
    const { modeName } = params.settings
    params = { ...params, tableParams }
    switch (modeName) {
        case "editable-mode": return EditableModeWrapper(params)
        case "print-mode": return PrintMode(params)
        default: return `Unknown how to render table mode [${modeName}]`
    }
}
