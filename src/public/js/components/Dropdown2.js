const select2FormatState = (state) => (!state.id) ? state.text : $(`<div class="flex justify-between px-1"><span>${state.text}</span><span>${state.id}</span></div>`)
const getEById = (id) => $("[id='" + id + "']")
// const removeParenthesis = (str) => (str.includes("()")) ? str.substring(0, str.length - 2) : str

let k = {}, listenersOfDropdown2 = {}, filtersOfDropdown2 = {}

const filterDropdown2 = (column_name, dataSource) => {
    if (filtersOfDropdown2[column_name] !== undefined) {
        const { filter_columns, filter_values } = filtersOfDropdown2[column_name]
        //Filter by filter_columns and filter_values
        for (let i = 0; i < filter_columns.length; i++) {
            const column = filter_columns[i]
            const value = filter_values[i]
            dataSource.forEach((row) => {
                if (row[column] === undefined) console.error("Column", column, " in filter_columns not found in", column_name, "(Relationships Screen)")
            })
            dataSource = dataSource.filter((row) => value == row[column])
        }
    }
    return dataSource
}

const onChangeDropdown2Reduce = (listener) => {
    const debug = false
    if (debug) console.log("Reduce listener", listener)
    const { column_name, table_name, listen_to_attrs, triggers } = listener
    let dataSource = k[table_name]
    if (debug) console.log("dataSource in k", dataSource)

    const constraintsValues = triggers.map((trigger) => getEById(trigger).val())
    if (debug) console.log(triggers, constraintsValues)

    for (let i = 0; i < triggers.length; i++) {
        const value = constraintsValues[i]
        const column = listen_to_attrs[i]
        if (!value) continue;
        if (debug) console.log("Applying", column, value, "to", table_name)
        dataSource = dataSource.filter((row) => value == row[column])
    }

    if (debug) console.log("DataSource", dataSource)
    // console.log('onChangeDropdown2Reduce')
    const lastSelected = getEById(column_name).val()
    // console.log("Selected", lastSelected)
    //TODO: make selected array if dropdown is multiple
    reloadDataToDropdown2(column_name, dataSource, [lastSelected * 1])
}
const onChangeGetSelectedObject = (listener) => {
    const { listen_to_fields, listen_to_tables } = listener
    const listen_to_field = listen_to_fields[0]
    const listen_to_table = listen_to_tables[0]
    const selectedId = getEById(listen_to_field).val()

    const table = k[listen_to_table]
    const selectedObject = table.find((i) => i['id'] == selectedId)

    return selectedObject
}

const onChangeDropdown2Assign = (listener) => {
    const debug = false
    if (debug) console.log("Assign", listener)
    const { column_name, listen_to_attrs } = listener
    const selectedObject = onChangeGetSelectedObject(listener)
    const listen_to_attr = listen_to_attrs[0]
    // const listen_to_attr = removeParenthesis(listen_to_attrs[0])
    if (debug) console.log(selectedObject, listen_to_attr)
    if (selectedObject !== undefined) {
        const theValue = selectedObject[listen_to_attr]
        if (theValue !== undefined) {
            // const column_name1 = removeParenthesis(column_name)
            if (debug) console.log(column_name, theValue)
            getEById(column_name).val(theValue)
            getEById(column_name).trigger('change')
        }
        else {
            console.error("Column", listen_to_attr, 'not found in', column_name, "(Listeners Screen)")
        }
    }
}
const onChangeDropdown2Dot = (listener) => {
    const debug = false
    if (debug) console.log("Dot", listener)
    const selectedObject = onChangeGetSelectedObject(listener)

    const { listen_to_attrs, column_name } = listener
    const listen_to_attr = listen_to_attrs[0]

    if (debug) console.log(selectedObject, listen_to_attr)
    // Unknown error
    if (selectedObject !== undefined) {
        const theValue = selectedObject[listen_to_attr]
        // console.log(theValue)
        if (debug) console.log(theValue)

        getEById(column_name).val(theValue)
        getEById(column_name).trigger('change')
        if (debug) console.log("Dotting", column_name, "with value", theValue)
    }
}

const onChangeDropdown2 = (name) => {
    // console.log("onChangeDropdown2", name, control.value)
    for (let i = 0; i < listenersOfDropdown2.length; i++) {
        let listener = listenersOfDropdown2[i]
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
    getEById(id).empty()

    let options = []

    dataSource = filterDropdown2(id, dataSource)
    if (dataSource === undefined) {
        console.error("DataSource not found in k for dropdown", id);
        return;
    }
    // console.log("Loading dataSource for", id, selected, dataSource)

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
    getEById(id).append(options)
    // console.log("Appended", id, 'with options has', options.length, 'items')

    getEById(id).select2({
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
    render += "onChange='onChangeDropdown2(\"" + name + "\")' "
    render += " " + multipleStr + " "
    render += "class='" + className + "' "
    render += ">"
    render += "</select>"

    return render
}

