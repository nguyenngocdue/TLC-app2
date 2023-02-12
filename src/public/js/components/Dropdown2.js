const select2FormatState = (state) => (!state.id) ? state.text : $(`<div class="flex justify-between px-1"><span>${state.text}</span><span>${state.id}</span></div>`)
const getEById = (id) => $("[id='" + id + "']")
// const removeParenthesis = (str) => (str.includes("()")) ? str.substring(0, str.length - 2) : str

let k = {}, listenersOfDropdown2 = {}, filtersOfDropdown2 = {}, debugListener = false

const filterDropdown2 = (column_name, dataSource) => {
    // const filtersOfDropdown2 = filtersOfDropdown2s[lineType]
    // console.log(filtersOfDropdown2s, filtersOfDropdown2, column_name, lineType)
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
    // const debugListener = true
    if (debugListener) console.log("Reduce listener", listener)
    const { column_name, table_name, listen_to_attrs, triggers } = listener
    let dataSource = k[table_name]
    if (debugListener) console.log("dataSource in k", dataSource)

    const constraintsValues = triggers.map((trigger) => getEById(trigger).val())
    if (debugListener) console.log(triggers, constraintsValues)

    const dumbIncludes = (array, item) => {
        for (let i = 0; i < array.length; i++) {
            if (array[i] == item) return true
        }
        return false
    }

    for (let i = 0; i < triggers.length; i++) {
        const value = constraintsValues[i]
        //console.log("value", constraintsValues[i], value, !value)
        const column = listen_to_attrs[i]
        if (column === undefined) console.log("The column to look up [", column, "] is not found in ...")
        if (!value) continue;
        if (debugListener) console.log("Applying", column, value, "to", table_name)
        dataSource = dataSource.filter((row) => {
            let result = null
            if (Array.isArray(row[column])) {
                result = dumbIncludes(row[column], value)
            } else {
                result = row[column] == value
            }
            if (debugListener) console.log("Result of reduce filter", row[column], value, result)
            return result
        })
    }

    if (debugListener) console.log("DataSource AFTER reduce", dataSource)
    // console.log('onChangeDropdown2Reduce')
    const lastSelected = getEById(column_name).val()
    // console.log("Selected", lastSelected)
    //TODO: make selected array if dropdown is multiple
    reloadDataToDropdown2(column_name, dataSource, [lastSelected * 1])
}
const onChangeGetSelectedObject2 = (listener) => {
    const { listen_to_fields, listen_to_tables } = listener
    const listen_to_field = listen_to_fields[0]
    const listen_to_table = listen_to_tables[0]
    const selectedId = getEById(listen_to_field).val()

    const table = k[listen_to_table]
    const selectedObject = table.find((i) => i['id'] == selectedId)

    return selectedObject
}

const onChangeDropdown2Assign = (listener) => {
    // const debugListener = true
    if (debugListener) console.log("Assign", listener)
    const { column_name, listen_to_attrs } = listener
    const selectedObject = onChangeGetSelectedObject2(listener)
    const listen_to_attr = listen_to_attrs[0]
    // const listen_to_attr = removeParenthesis(listen_to_attrs[0])
    if (debugListener) console.log("Selected Object:", selectedObject, " - listen_to_attr:", listen_to_attr)
    if (selectedObject !== undefined) {
        const theValue = selectedObject[listen_to_attr]
        if (theValue !== undefined) {
            // const column_name1 = removeParenthesis(column_name)
            if (debugListener) console.log(column_name, theValue)
            getEById(column_name).val(theValue)
            getEById(column_name).trigger('change')
        }
        else {
            console.error("Column", listen_to_attr, 'not found in', column_name, "(Listeners Screen)")
        }
    }
}
const onChangeDropdown2Dot = (listener) => {
    // const debugListener = true
    if (debugListener) console.log("Dot", listener)
    const selectedObject = onChangeGetSelectedObject2(listener)

    const { listen_to_attrs, column_name } = listener
    const listen_to_attr = listen_to_attrs[0]

    if (debugListener) console.log(selectedObject, listen_to_attr)
    // Unknown error
    if (selectedObject !== undefined) {
        const theValue = selectedObject[listen_to_attr]
        // console.log(theValue)
        if (debugListener) console.log(theValue)

        getEById(column_name).val(theValue)
        getEById(column_name).trigger('change')
        if (debugListener) console.log("Dotting", column_name, "with value", theValue)
    }
}

const onChangeDropdown2DateOffset = (listener) => {
    if (debugListener) console.log("Date Offset", listener)
    const selectedObject = onChangeGetSelectedObject2(listener)

    const { listen_to_attrs, column_name } = listener
    const listen_to_attr = listen_to_attrs[0]
    // console.log(listen_to_attr, column_name, selectedObject)
    if (selectedObject !== undefined) {
        const theValue = selectedObject[listen_to_attr]
        if (debugListener) console.log(theValue)

        const theValueDate = moment().add(theValue, 'days').format("YYYY-MM-DD HH:mm:ss");
        if (debugListener) console.log(theValueDate)

        getEById(column_name).val(theValueDate)
        getEById(column_name).trigger('change')
        if (debugListener) console.log("Date Offset", column_name, "with value", theValueDate)
    }
}

const onChangeDropdown2 = (name) => {
    // console.log("onChangeDropdown2", name)
    // console.log(listenersOfDropdown2)
    for (let i = 0; i < listenersOfDropdown2.length; i++) {
        let listener = listenersOfDropdown2[i]
        const { triggers, listen_action } = listener
        // console.log(listen_action, name, triggers)
        if (triggers.includes(name)) {
            // console.log("listen_action", listen_action)
            switch (listen_action) {
                case "reduce":
                    onChangeDropdown2Reduce(listener)
                    break
                case "assign":
                    onChangeDropdown2Assign(listener)
                    break
                case "dot":
                    onChangeDropdown2Dot(listener)
                    break
                case "date_offset":
                    onChangeDropdown2DateOffset(listener)
                    break
                default:
                    console.error("Unknown listen_action", listen_action, "of", name);
                    break;
            }
        }
    }
}

const reloadDataToDropdown2 = (id, dataSource, selected) => {
    if (dataSource === undefined) return;
    getEById(id).empty()

    let options = []
    dataSource = filterDropdown2(id, dataSource)
    // console.log("Loading dataSource for", id, selected, dataSource)

    for (let i = 0; i < dataSource.length; i++) {
        let item = dataSource[i]
        selectedStr = (dataSource.length === 1) ? 'selected' : (selected.includes(item.id) ? "selected" : "")
        option = "<option value='" + item.id + "' title='" + item.description + "' " + selectedStr + " >"
        option += item.name
        option += "</option>"
        options.push(option)
    }
    options.unshift("<option value=''></option>")
    getEById(id).append(options)
    // console.log("Appended", id, 'with options has', options.length, 'items')

    getEById(id).select2({
        placeholder: "Please select..."
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

