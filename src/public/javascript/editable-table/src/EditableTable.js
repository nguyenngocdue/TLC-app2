import { EditableModeWrapper } from './TableMode/EditableModeWrapper'
import { PrintableMode } from './TableMode/PrintableMode'

export const EditableTable = (params) => {
    // console.log(params)
    const { modeName } = params.settings
    switch (modeName) {
        case "editable-mode": return EditableModeWrapper(params)
        case "printable-mode": return PrintableMode(params)
        default: return `Unknown how to render table mode [${modeName}]`
    }
}
