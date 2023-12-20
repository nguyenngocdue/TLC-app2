import { EditableModeWrapper } from './TableMode/EditableModeWrapper'
import { PrintMode } from './TableMode/PrintMode'



export const EditableTable = (params) => {
    // console.log(params)
    const { modeName } = params.settings
    switch (modeName) {
        case "editable-mode": return EditableModeWrapper(params)
        case "print-mode": return PrintMode(params)
        default: return `Unknown how to render table mode [${modeName}]`
    }
}
