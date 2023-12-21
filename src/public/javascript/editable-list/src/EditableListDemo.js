import { EditableList } from "./EditableList"
import { helloList } from "./functions"

const params = { dataSource: helloList, width: 200, }

export const EditableListDemo = (divId) => {
    const select = `<div class="p-5 flex ">
        <div>
        ${EditableList({ ...params, name: "lst001a", id: "lst001a", selected: 3, })}
        </div>
        ___
        <div>
        ${EditableList({ ...params, name: "lst001b", id: "lst001b", selected: 5, allowFilter: true, })}
        </div>
        ___
        <div>
        ${EditableList({ ...params, name: "lst002a", id: "lst002a", selected: [1, 4], })}
        </div>
        ___
        <div>
        ${EditableList({ ...params, name: "lst002b", id: "lst002b", selected: [2, 3], allowFilter: true, })}
        </div>
    </div>`
    $("#" + divId).html(select)
}