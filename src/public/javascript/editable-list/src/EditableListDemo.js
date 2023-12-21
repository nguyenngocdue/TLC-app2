import { EditableList } from "./EditableList"
import { helloList } from "./functions"

const params = { dataSource: helloList(), width: 200, }

export const EditableListDemo = (divId) => {
    const select = `<div class="p-5 flex ">
        <div>
        ${EditableList({ ...params, name: "lst001a", id: "lst001a", selected: 'a1', })}
        </div>
        ___
        <div>
        ${EditableList({ ...params, name: "lst001b", id: "lst001b", selected: 'a2', allowFilter: true, })}
        </div>
        ___
        <div>
        ${EditableList({ ...params, name: "lst002a", id: "lst002a", selected: ['a3', 'a4'], })}
        </div>
        ___
        <div>
        ${EditableList({ ...params, name: "lst002b", id: "lst002b", selected: ['a5', 'a6'], allowFilter: true, })}
        </div>
    </div>`
    $("#" + divId).html(select)
}