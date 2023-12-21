import { EditableList } from "./EditableList"

const params = {

    dataSource: {
        1: { name: "hello 1" },
        2: { name: "hello 2" },
        3: { name: "hello 3" },
        4: { name: "hello 4" },
        5: { name: "hello 5" },
        6: { name: "hello 6" },
        7: { name: "hello 7" },
        8: { name: "hello 8" },
        9: { name: "hello 9" },
        10: { name: "hello 10" },
        a11: { name: "hello 11" },
        a12: { name: "hello 12" },
        a13: { name: "hello 13" },
        a14: { name: "hello 14" },
        a15: { name: "hello 15" },
        a16: { name: "hello 16" },
        a17: { name: "hello 17" },
        a18: { name: "hello 18" },
        a19: { name: "hello 19" },
        a20: { name: "hello 20" },
    },
    width: 200,
}

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