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
        11: { name: "hello 11" },
        12: { name: "hello 12" },
        13: { name: "hello 13" },
        14: { name: "hello 14" },
        15: { name: "hello 15" },
        16: { name: "hello 16" },
        17: { name: "hello 17" },
        18: { name: "hello 18" },
        19: { name: "hello 19" },
        20: { name: "hello 20" },
    },
    width: 200,
}

export const EditableListDemo = (divId) => {
    const select = `<div class="p-5 flex ">
        <div>
        ${EditableList({ ...params, name: "lst001a", id: "lst001a", })}
        </div>
        ___
        <div>
        ${EditableList({ ...params, name: "lst001b", id: "lst001b", allowFilter: true, })}
        </div>
        ___
        <div>
        ${EditableList({ ...params, name: "lst002a", id: "lst002a", })}
        </div>
        ___
        <div>
        ${EditableList({ ...params, name: "lst002b", id: "lst002b", allowFilter: true, })}
        </div>
    </div>`
    $("#" + divId).html(select)
}