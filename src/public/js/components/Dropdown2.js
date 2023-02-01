const select2FormatState = (state) => (!state.id) ? state.text : $(`<div class="flex justify-between px-1"><span>${state.text}</span><span>${state.id}</span></div>`)

let k = {}, listeners = {}, table = ""

const onChangeDropdown2Reduce = (listener) => {
    const debug = false
    if (debug) console.log("Reduce listener", listener)
    const { column_name, table_name, listen_to_attrs } = listener
    let dataSource = k[table_name]
    if (debug) console.log("dataSource in k", dataSource)

    const constraints = listener['triggers']
    const constraintsValues = constraints.map((constraint) => $("#" + constraint).val())
    if (debug) console.log(constraints, constraintsValues)

    for (let i = 0; i < constraints.length; i++) {
        const value = constraintsValues[i]
        const column = listen_to_attrs[i]
        if (!value) continue;
        if (debug) console.log("Applying", column, value, "to", table_name)
        dataSource = dataSource.filter((row) => value == row[column])
    }

    if (debug) console.log("DataSource", dataSource)
    // console.log('onChangeDropdown2Reduce')
    const lastSelected = $("#" + column_name).val()
    // console.log("Selected", lastSelected)
    //TODO: make selected array if dropdown is multiple
    reloadDataToDropdown2(column_name, dataSource, [lastSelected * 1])
}
const onChangeDropdown2Assign = (listener) => {
    console.log("Assign", listener)
}
const onChangeDropdown2Dot = (listener) => {
    const debug = false
    if (debug) console.log("Dot", listener)
    const { listen_to_fields, listen_to_tables, listen_to_attrs, column_name } = listener
    const listen_to_field = listen_to_fields[0]
    const listen_to_table = listen_to_tables[0]
    const listen_to_attr = listen_to_attrs[0]
    const selectedId = $("#" + listen_to_field).val()

    const table = k[listen_to_table]
    const selectedObject = table.find((i) => i['id'] == selectedId)
    const theValue = selectedObject[listen_to_attr]
    if (debug) console.log(selectedId, table, selectedObject, listen_to_attr, theValue)

    $("#" + column_name).val(theValue)
    $("#" + column_name).trigger('change')
    if (debug) console.log("Assigning", column_name, "with value", theValue)
}

const onChangeDropdown2 = (control, name) => {
    // console.log("onChangeDropdown2", name, control.value)
    for (let i = 0; i < listeners.length; i++) {
        let listener = listeners[i]
        const { triggers, listen_action } = listener
        if (triggers.includes(name)) {
            switch (listen_action) {
                case "reduce":
                    onChangeDropdown2Reduce(listener)
                    break;
                case "assign":
                    onChangeDropdown2Assign(listener)
                    break;
                case "dot":
                    onChangeDropdown2Dot(listener)
                    break;
                default:
                    console.warn("Unknown listen_action", listen_action);
                    break;
            }
        }
    }
}

const reloadDataToDropdown2 = (id, dataSource, selected) => {
    // console.log("Loading dataSource for", id, selected)
    $("#" + id).empty()
    let options = []

    for (let i = 0; i < dataSource.length; i++) {
        let item = dataSource[i]
        if (dataSource.length === 1) {
            selectedStr = 'selected'
        } else {
            selectedStr = selected.includes(item.id) ? "selected" : ""
        }
        option = "<option value='" + item.id + "' title='" + item.description + "' " + selectedStr + " >"
        option += item.name
        option += "</option>"
        options.push(option)
    }
    options.unshift("<option value=''></option>")
    $("#" + id).append(options)
    // console.log("Appended", id, 'with options has', options.length, 'items')

    $("#" + id).select2({
        placeholder: "Please select"
        , allowClear: true
        , templateResult: select2FormatState
    });

}

const Dropdown2 = ({ id, name, className, multipleStr }) => {
    let render = ""
    render += "<select "
    render += "id='" + id + "' "
    render += "name='" + name + "' "
    render += "onChange='onChangeDropdown2(this,\"" + name + "\")' "
    render += " " + multipleStr + " "
    render += "class='" + className + "' "
    render += ">"
    render += "</select>"

    return render
}

