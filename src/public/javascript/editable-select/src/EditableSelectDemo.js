import { EditableSelect } from "./EditableSelect"

export const EditableSelectDemo = (divId) => {
    const select = `Select: ${EditableSelect()}`
    $("#" + divId).html(select)
}