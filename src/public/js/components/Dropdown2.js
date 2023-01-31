const select2FormatState = (state) => (!state.id) ? state.text : $(`<div class="flex justify-between px-1"><span>${state.text}</span><span>${state.id}</span></div>`)

let k = {}, listeners = {}, table = ""

const onChangeDropdown2Reduce = (column_name, listener) => {
    console.log("Reduce", column_name, listener)
}
const onChangeDropdown2Assign = (column_name, listener) => {
    console.log("Assign", column_name, listener)
}
const onChangeDropdown2Dot = (column_name, listener) => {
    console.log("Dot", column_name, listener)
}

const onChangeDropdown2 = (control, name) => {
    console.log(control.value, name, listeners)
    for (let i = 0; i < listeners.length; i++) {
        let listener = listeners[i]
        const { triggers } = listener
        if (triggers.includes(name)) {
            const { listen_action, column_name } = listener
            switch (listen_action) {
                case "reduce":
                    onChangeDropdown2Reduce(column_name, listener)
                    break;
                case "assign":
                    onChangeDropdown2Assign(column_name, listener)
                    break;
                case "dot":
                    onChangeDropdown2Dot(column_name, listener)
                    break;
                default:
                    console.warn("Unknown listen_action", listen_action);
                    break;
            }
        }
    }

}

const Dropdown2 = ({ id, name, className, multipleStr, table, selected }) => {
    selected = JSON.parse(selected)
    const dataSource = k[table]
    // console.log("Creating Dropdown2: ", name, table, selected)

    let render = ""
    render += "<select "
    render += "id='" + id + "' "
    render += "name='" + name + "' "
    render += "onChange='onChangeDropdown2(this,\"" + name + "\")' "
    render += " " + multipleStr + " "
    render += "class='" + className + "' "
    render += ">"
    render += "<option value=''></option>"
    for (let i = 0; i < dataSource.length; i++) {
        let item = dataSource[i]
        let option = ''
        selectedStr = selected.includes(item.id) ? "selected" : ""
        option += "<option value='" + item.id + "' title='" + item.description + "' " + selectedStr + " >"
        option += item.name
        option += "</option>"
        render += option
    }
    render += "</select>"

    return render
}

