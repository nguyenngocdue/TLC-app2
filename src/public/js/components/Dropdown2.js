const select2FormatState = (state) => (!state.id) ? state.text : $(`<div class="flex justify-between px-1"><span>${state.text}</span><span>${state.id}</span></div>`)

let k = {}
let table = ""

const Dropdown2 = ({ id, name, className, multipleStr, table, selected }) => {
    selected = JSON.parse(selected)
    const dataSource = k[table]
    console.log("Creating Dropdown2: ", name, table, selected)

    let render = ""
    render += "<select id='" + id + "' name='" + name + "' " + multipleStr + " class='" + className + "'>"
    render += "<option value=''></option>"
    for (let i = 0; i < dataSource.length; i++) {
        let item = dataSource[i]
        let option = ''
        selectedStr = selected.includes(item.id) ? "selected" : ""
        option += "<option value='" + item.id + "' title='" + item.title + "' " + selectedStr + " >"
        option += item.label
        option += "</option>"
        render += option
    }
    render += "</select>"

    return render
}

