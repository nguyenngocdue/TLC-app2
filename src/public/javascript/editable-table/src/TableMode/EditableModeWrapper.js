import { EditableMode } from "./EditableMode"

export const EditableModeWrapper = (params) => {
    const editableMode = EditableMode(params)

    return `${editableMode}`
}