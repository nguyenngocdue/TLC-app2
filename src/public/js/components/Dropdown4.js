let runOnce = {}
//Similar to includes, this will be checking both numbers and strings
const dumbIncludes = (item, array) => {
    if (Array.isArray(array)) {
        for (let i = 0; i < array.length; i++) {
            if (array[i] == item) return true
        }
        return false
    } else {
        return item == array
        // console.log("IMPLEMENT ME", item, array)
    }
}
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
    // console.log(filtersOfDropdown4, column_name)
    if (filtersOfDropdown4[column_name] !== undefined) {
        const { filter_columns, filter_values } = filtersOfDropdown4[column_name]
        //Filter by filter_columns and filter_values
        for (let i = 0; i < filter_columns.length; i++) {
            const column = filter_columns[i]
            const value = filter_values[i]
            dataSource.forEach((row) => {
                if (row[column] === undefined) {
                    console.error("Column", column, " in filter_columns not found in", column_name, "(Relationships Screen)")
                    // } else {
                    //     console.log("Column [", column, "] in filter_columns found in", column_name, "(Relationships Screen)");
                }
            })
            dataSource = dataSource.filter((row) => value == row[column])
        }
    }
    // console.log("Filtered")
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
                result = dumbIncludes(value, row[column])
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

        const theValueDate = moment().add(theValue, 'days').format("DD/MM/YYYY HH:mm");
        if (debugListener) console.log(theValueDate)

        getEById(id).val(theValueDate)
        getEById(id).trigger('change')
        if (debugListener) console.log("Date Offset", id, "with value", theValueDate)
    }
}

const onChangeDropdown4Expression = (listener, table01Name, rowIndex) => {
    // const debugListener = true
    if (debugListener) console.log("Expression", listener, table01Name, rowIndex)
    const { expression, column_name } = listener
    let expression1 = expression

    const vars = getAllVariablesFromExpression(expression)
    for (let i = 0; i < vars.length; i++) {
        const varName = vars[i]
        if (['Math', 'round', 'ceil', 'trunc', 'toDateString'].includes(varName)) continue
        const varNameFull = makeIdFrom(table01Name, varName, rowIndex)
        let varValue = getEById(varNameFull).val() || 0
        if (varValue && isNaN(varValue)) {
            if (varValue.includes(":") && varValue.includes("/")) {
                const datetime = varValue.split(" ")
                const date = datetime[0]
                const time = datetime[1]
                varValue = getDaysFromDate(date) * 24 * 3600 + getSecondsFromTime(time)
            }
            else {
                if (varValue.includes(":")) {
                    varValue = getSecondsFromTime(varValue)
                } else if (varValue.includes("/")) {
                    varValue = getDaysFromDate(varValue)
                }
            }
            if (debugListener) console.log(varName, varValue)
        }

        if (debugListener) console.log(varName, "=", varValue)
        expression1 = expression1.replace(varName, varValue)
    }
    const result = eval(expression1)
    if (debugListener) console.log(column_name, '=', expression1, result)
    const id = makeIdFrom(table01Name, column_name, rowIndex)
    getEById(id).val(result)
    getEById(id).trigger('change')
}

const onChangeDropdown4AjaxRequestScalar = (listener, table01Name, rowIndex) => {
    // const debugListener = true
    if (debugListener) console.log("AjaxRequestScalar", listener)
    const { triggers, expression: url, column_name } = listener
    const { ajax_response_attribute, ajax_item_attribute, ajax_default_value } = listener

    let enoughParams = true
    const data = {}
    const missingParams = []
    for (let i = 0; i < triggers.length; i++) {
        const name = makeIdFrom(table01Name, triggers[i], rowIndex)
        let value = getEById(name).val()
        if (value === null || value === '' || value === undefined) {
            enoughParams = false
            missingParams.push(name)
        }
        data[triggers[i]] = value
    }
    if (enoughParams) {
        if (debugListener) console.log("Sending AjaxRequest with data:", data, url)
        $.ajax({
            url, data,
            success: (response) => {
                let value = -1
                if (response[ajax_response_attribute][0] === undefined) {
                    value = ajax_default_value
                    if (debugListener) console.log("Response empty", ajax_response_attribute, ', assigning default value', ajax_default_value)
                } else if (response[ajax_response_attribute][0][ajax_item_attribute] === undefined) {
                    value = ajax_default_value
                    if (debugListener) console.log("Requested column", ajax_item_attribute, 'not found, assigning default value', ajax_default_value)
                } else {
                    value = response[ajax_response_attribute][0][ajax_item_attribute]
                }
                const id = makeIdFrom(table01Name, column_name, rowIndex)
                if (debugListener) console.log("Assigning", id, "with value", value)
                if (debugListener) console.log("Response", response)
                getEById(id).val(value)
                getEById(id).trigger('change')
            },
            error: (response) => console.error(response)
        })
    } else {
        if (debugListener) console.log("Sending AjaxRequest cancelled as not enough parameters", missingParams)
    }
}

const onChangeDropdown4 = ({ name, table01Name, rowIndex, lineType, saveOnChange }) => {
    // console.log("onChangeDropdown4", name, table01Name, rowIndex, lineType, saveOnChange)
    // console.log("listenersOfDropdown4s", listenersOfDropdown4s, table01Name)
    const fieldName = getFieldNameInTable01FormatJS(name, table01Name)
    if (saveOnChange) {
        const lineId = makeIdFrom(table01Name, 'id', rowIndex)
        const id = getEById(lineId).val()
        const value = getEById(name).val()
        const data = { [fieldName]: value }
        console.log(data)
        $.ajax({
            url: '/api/v1/hr/create_overtime_request_line/' + id,
            type: 'POST',
            data,
            success: (response) => { console.log(response) }
        })
    }
    const listenersOfDropdown4 = listenersOfDropdown4s[table01Name]
    for (let i = 0; i < listenersOfDropdown4.length; i++) {
        let listener = listenersOfDropdown4[i]
        const { triggers, listen_action } = listener
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
                case "expression":
                    onChangeDropdown4Expression(listener, table01Name, rowIndex)
                    break
                case "ajax_request_scalar":
                    onChangeDropdown4AjaxRequestScalar(listener, table01Name, rowIndex)
                    break
                default:
                    console.error("Unknown listen_action", listen_action, "of", name);
                    break;
            }
        }
    }
}

const reloadDataToDropdown4 = (id, dataSource, table01Name, selected) => {
    // console.log("reloadDataToDropdown4", id, dataSource, table01Name, selected)
    // console.log(table01Name, id, selected)
    if (dataSource === undefined) return;
    getEById(id).empty()

    let options = []
    // console.log("Loading dataSource for", id, selected, dataSource)
    dataSource = filterDropdown4(id, dataSource, table01Name)

    for (let i = 0; i < dataSource.length; i++) {
        let item = dataSource[i]
        selectedStr = (dataSource.length === 1) ? 'selected' : (dumbIncludes(item.id, selected) ? "selected" : "")
        // console.log("During making option list", item.id, item.name, "================================", selectedStr)
        const title = item.description || makeId(item.id)
        option = "<option value='" + item.id + "' title='" + title + "' " + selectedStr + " >"
        option += item.name || "Nameless #" + item.id
        option += "</option>"
        options.push(option)
    }
    options.unshift("<option value=''></option>")
    getEById(id).append(options)
    // console.log("Appended", id, 'with options has', options.length, 'items')

    getEById(id).select2({
        placeholder: "Please select"
        // , allowClear: true //<<This make a serious bug when user clear and re-add a multiple dropdown, it created a null element
        , templateResult: select2FormatState
    });
    // getEById(id).trigger("change")
}

const documentReadyDropdown4 = ({ id, table01Name, selectedJson, table }) => {
    if (runOnce[id]) {
        // console.log("cancel reload")
        getEById(id).select2({
            placeholder: "Please select"
            // , allowClear: true //<<This make a serious bug when user clear and re-add a multiple dropdown, it created a null element
            , templateResult: select2FormatState
        });
        return
    }
    runOnce[id] = true
    selectedJson = selectedJson.replace(/\\/g, '\\\\') //<< Replace \ to \\ EG. ["App\Models\Qaqc_mir"] to ["App\\Models\\Qaqc_mir"]
    selectedJson = JSON.parse(selectedJson)
    dataSourceDropdown = k[table];
    if (dataSourceDropdown === undefined) console.error("Key " + table + " not found in k[]");
    reloadDataToDropdown4(id, dataSourceDropdown, table01Name, selectedJson)
    listenersOfDropdown4s[table01Name].forEach((listener) => {
        const fieldName = getFieldNameInTable01FormatJS(id, table01Name)
        if (listener.triggers.includes(fieldName) && listener.listen_action === 'reduce') {
            // console.log("I am a trigger of reduce, I have to trigger myself when form load ",id )
            getEById(id).trigger('change')
        }
    })
}