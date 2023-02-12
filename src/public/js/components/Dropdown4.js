// const select2FormatState = (state) => (!state.id) ? state.text : $(`<div class="flex justify-between px-1"><span>${state.text}</span><span>${state.id}</span></div>`)
// const getEById = (id) => $("[id='" + id + "']")
// const removeParenthesis = (str) => (str.includes("()")) ? str.substring(0, str.length - 2) : str

const makeIdFrom = (table01Name, fieldName, rowIndex) => table01Name + "[" + fieldName + "][" + rowIndex + "]"

const getFieldNameInTable01FormatJS = (name, table01Name) => {
    const isStartWith = table01Name && name.startsWith(table01Name)
    if (isStartWith) {
        name = name.substr(table01Name.length + 1)// table01[hse_incident_report_id][0] => hse_incident_report_id][0]
        name = name.substr(0, name.indexOf("]"))
    }
    return name;
}
// console.log(getFieldNameInTable01FormatJS('table01[hello][9]', 'table01'))
// console.log(getFieldNameInTable01FormatJS('table99[help][100]', 'table99'))


let listenersOfDropdown4s = {}, filtersOfDropdown4s = {}

const filterDropdown4 = (id, dataSource, table01Name) => {
    const filtersOfDropdown4 = filtersOfDropdown4s[table01Name]
    const column_name = getFieldNameInTable01FormatJS(id, table01Name)
    // console.log(filtersOfDropdown4s, filtersOfDropdown4, column_name)
    if (filtersOfDropdown4[column_name] !== undefined) {
        const { filter_columns, filter_values } = filtersOfDropdown4[column_name]
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

const onChangeDropdown4Reduce = (listener, table01Name, rowIndex, lineType) => {
    // const debugListener = true
    if (debugListener) console.log("Reduce listener", listener)
    const { column_name, table_name, listen_to_attrs, triggers } = listener
    let dataSource = k[table_name]
    if (debugListener) console.log("dataSource in k", dataSource)

    const constraintsValues = triggers.map((trigger) => getEById(makeIdFrom(table01Name, trigger, rowIndex)).val())
    if (debugListener) console.log(triggers, constraintsValues)

    const dumbIncludes = (array, item) => {
        for (let i = 0; i < array.length; i++) {
            if (array[i] == item) return true
        }
        return false
    }

    for (let i = 0; i < triggers.length; i++) {
        const value = constraintsValues[i]
        // console.log("value", constraintsValues[i], value, !value)
        const column = listen_to_attrs[i]
        if (column === undefined) console.log("The column to look up [", column, "] is not found in ...")
        if (!value) continue;
        if (debugListener) console.log("Applying", column, value, "to", table_name, 'by column', column)
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
    // console.log('onChangeDropdown4Reduce')
    const id = makeIdFrom(table01Name, column_name, rowIndex)
    const lastSelected = getEById(id).val()
    // console.log("Selected", lastSelected)
    //TODO: make selected array if dropdown is multiple
    reloadDataToDropdown4(id, dataSource, table01Name, [lastSelected * 1])
}
const onChangeGetSelectedObject4 = (listener, table01Name, rowIndex) => {
    const { listen_to_fields, listen_to_tables } = listener
    const listen_to_field = listen_to_fields[0]
    const listen_to_table = listen_to_tables[0]
    const id = makeIdFrom(table01Name, listen_to_field, rowIndex)
    const selectedId = getEById(id).val()

    const table = k[listen_to_table]
    const selectedObject = table.find((i) => i['id'] == selectedId)

    return selectedObject
}

const onChangeDropdown4Assign = (listener, table01Name, rowIndex) => {
    // const debugListener = true
    if (debugListener) console.log("Assign", listener)
    const { column_name, listen_to_attrs } = listener
    const selectedObject = onChangeGetSelectedObject4(listener, table01Name, rowIndex)
    const listen_to_attr = listen_to_attrs[0]
    // const listen_to_attr = removeParenthesis(listen_to_attrs[0])
    if (debugListener) console.log("Selected Object:", selectedObject, " - listen_to_attr:", listen_to_attr)
    if (selectedObject !== undefined) {
        const theValue = selectedObject[listen_to_attr]
        const id = makeIdFrom(table01Name, column_name, rowIndex)
        if (theValue !== undefined) {
            // const column_name1 = removeParenthesis(id)
            if (debugListener) console.log("Set value of", id, "to", theValue)
            getEById(id).val(theValue)
            getEById(id).trigger('change')
        }
        else {
            console.error("Column", listen_to_attr, 'not found in', id, "(Listeners Screen)")
        }
    }
}
const onChangeDropdown4Dot = (listener, table01Name, rowIndex) => {
    // const debugListener = true
    if (debugListener) console.log("Dot", listener)
    const selectedObject = onChangeGetSelectedObject4(listener, table01Name, rowIndex)

    const { listen_to_attrs, column_name } = listener
    const listen_to_attr = listen_to_attrs[0]

    if (debugListener) console.log(selectedObject, listen_to_attr)
    // Unknown error
    if (selectedObject !== undefined) {
        const theValue = selectedObject[listen_to_attr]
        // console.log(theValue)
        const id = makeIdFrom(table01Name, column_name, rowIndex)
        if (debugListener) console.log(id, theValue)

        getEById(id).val(theValue)
        getEById(id).trigger('change')
        if (debugListener) console.log("Dotting", id, "with value", theValue)
    }
}

const onChangeDropdown4DateOffset = (listener, table01Name, rowIndex) => {
    // const debugListener = true
    if (debugListener) console.log("Date Offset", listener)
    const selectedObject = onChangeGetSelectedObject4(listener, table01Name, rowIndex)

    const { listen_to_attrs, column_name } = listener
    const listen_to_attr = listen_to_attrs[0]
    // console.log(listen_to_attr, column_name, selectedObject)
    if (selectedObject !== undefined) {
        const theValue = selectedObject[listen_to_attr]
        const id = makeIdFrom(table01Name, column_name, rowIndex)
        if (debugListener) console.log(theValue)

        const theValueDate = moment().add(theValue, 'days').format("YYYY-MM-DD HH:mm:ss");
        if (debugListener) console.log(theValueDate)

        getEById(id).val(theValueDate)
        getEById(id).trigger('change')
        if (debugListener) console.log("Date Offset", id, "with value", theValueDate)
    }
}

const onChangeDropdown4 = (name, lineType, table01Name, rowIndex) => {
    // console.log("onChangeDropdown4", name, lineType, table01Name, rowIndex)
    // console.log("listenersOfDropdown4s", listenersOfDropdown4s)
    const listenersOfDropdown4 = listenersOfDropdown4s[table01Name]
    for (let i = 0; i < listenersOfDropdown4.length; i++) {
        let listener = listenersOfDropdown4[i]
        const { triggers, listen_action } = listener
        const fieldName = getFieldNameInTable01FormatJS(name, table01Name)
        // console.log(triggers, listen_action, name, fieldName, table01Name, rowIndex)
        if (triggers.includes(fieldName)) {
            // console.log("listen_action", listen_action)
            switch (listen_action) {
                case "reduce":
                    onChangeDropdown4Reduce(listener, table01Name, rowIndex, lineType)
                    break
                case "assign":
                    onChangeDropdown4Assign(listener, table01Name, rowIndex)
                    break
                case "dot":
                    onChangeDropdown4Dot(listener, table01Name, rowIndex)
                    break
                case "date_offset":
                    onChangeDropdown4DateOffset(listener, table01Name, rowIndex)
                    break
                default:
                    console.error("Unknown listen_action", listen_action, "of", name);
                    break;
            }
        }
    }
}

const reloadDataToDropdown4 = (id, dataSource, table01Name, selected) => {
    // console.log("reloadDataToDropdown4", id, dataSource, table01Name, rowIndex, lineType, selected)
    if (dataSource === undefined) return;
    getEById(id).empty()

    let options = []
    // console.log("Loading dataSource for", id, selected, dataSource)
    dataSource = filterDropdown4(id, dataSource, table01Name)

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
        placeholder: "Please select"
        , allowClear: true
        , templateResult: select2FormatState
    });

}

const Dropdown4 = ({ id, name, className, multipleStr, lineType, table01Name, rowIndex }) => {

    let render = ""
    render += "<select "
    render += "id='" + id + "' "
    render += "name='" + name + "' "
    render += "onChange='onChangeDropdown4(\"" + name + "\", \"" + lineType + "\", \"" + table01Name + "\", " + rowIndex + ")' "
    render += " " + multipleStr + " "
    render += "class='" + className + "' "
    render += ">"
    render += "</select>"

    // console.log(render)

    return render
}

