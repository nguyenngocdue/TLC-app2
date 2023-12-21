import { EditableList } from "./EditableList"

const params = {
    dataSource: {}
}

export const EditableListDemo = (divId) => {
    const select = `${EditableList(params)}`
    $("#" + divId).html(select)
}