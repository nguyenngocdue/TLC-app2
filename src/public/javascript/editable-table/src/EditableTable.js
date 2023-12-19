import { EditableModeWrapper } from './TableMode/EditableModeWrapper'
import { PrintMode } from './TableMode/PrintMode'

const tableParams = {
    id: "tableId",
    maxH: 1000,
    showPaginationTop: false,
    tableWidth: 100,
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
