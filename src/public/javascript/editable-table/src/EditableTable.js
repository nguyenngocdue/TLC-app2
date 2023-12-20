import { EditableModeWrapper } from './TableMode/EditableModeWrapper'
import { PrintableMode } from './TableMode/PrintableMode'

import { postRender } from './PostRender'

const tableRenderer = (params) => {
    const { modeName } = params.settings
    switch (modeName) {
        case "editable-mode": return EditableModeWrapper(params)
        case "printable-mode": return PrintableMode(params)
        default: return `Unknown how to render table mode [${modeName}]`
    }
}

export const EditableTable = (params) => {
    // console.log(params)
    const table = tableRenderer(params)
    postRender(params);
    return table
}
