let k = {}, listenersOfDropdown2 = {}, filtersOfDropdown2 = {}, debugListener = false
const makeIdForNumber = (n) => "#" + String(n).padStart(6, '0').substring(0, 3) + "." + String(n).padStart(6, '0').substring(3)
const makeId = (n) => isNaN(n) ? "" : makeIdForNumber(n)
const select2FormatState = (state) => (!state.id) ? state.text : $(`<div class="flex justify-between px-1"><span>${state.text}</span><span>${makeId(state.id)}</span></div>`)
const getEById = (id) => $("[id='" + id + "']")

const getIsMultipleOfE = (id) => getEById(id)[0].hasAttribute("multiple")
const getControlTypeOfE = (id) => getEById(id).attr("controlType")
const getColSpanOfE = (id) => getEById(id).attr("colSpan")
const getAllVariablesFromExpression = (str) => {
    const regex = new RegExp('[a-zA-Z_]+[a-zA-Z0-9]*', 'gm'), result = []
    let m;
    while ((m = regex.exec(str)) !== null) {
        // This is necessary to avoid infinite loops with zero-width matches
        if (m.index === regex.lastIndex) regex.lastIndex++;
        m.forEach((match) => result.push(match));
    }
    // console.log(result)
    return result
}
const getSecondsFromTime = (hms) => {
    var a = hms.split(':'); // split it at the colons
    switch (a.length) {
        case 1: return (+a[0])
        case 2: return (+a[0]) * 60 * 60 + (+a[1]) * 60 // HH:MM
        case 3: return (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2])
    }
}
const getDaysFromDate = (dmy) => moment(dmy, "DD/MM/YYYY").diff(moment('1970-01-01'), 'days')
const getValueOfEById = (id) => {
    const isMultipleOfE = getIsMultipleOfE(id)
    const controlType = getControlTypeOfE(id)
    // console.log(id, isMultipleOfE, controlType)
    if (controlType === "radio_or_checkbox") {
        const control = isMultipleOfE ? "checkbox" : "radio"
        return $("input:" + control + "[name='" + id + "']:checked").val();
    }
    return getEById(id).val()
}
const setValueOfEById = (id, value) => {
    const debugSetValue = false
    const isMultipleOfE = getIsMultipleOfE(id)
    const controlType = getControlTypeOfE(id)
    // console.log(id, isMultipleOfE, controlType)
    let queryStr = ''
    if (controlType === "radio_or_checkbox") {
        // console.log(id, value)
        if (isMultipleOfE) {
            // $("input:checkbox[name='dropdownMonitors()[]']").prop("checked",false)
            queryStr = "input:checkbox[name='" + id + "[]']"
            $(queryStr).prop("checked", false)
            if (debugSetValue) console.log("Unchecked all", queryStr);
            value.forEach(id_id => {
                queryStr = "input:checkbox[name='" + id + "[]'][value=" + id_id + "]";
                $(queryStr).prop("checked", true);
                if (debugSetValue) console.log("Checking", queryStr);
            })
        } else {
            // $("input:radio[name='assignee_1'][value=765]").prop("checked",true)
            if (value == null) {
                queryStr = "input:radio[name='" + id + "']"
                $(queryStr).prop("checked", false);
            } else {
                queryStr = "input:radio[name='" + id + "'][value=" + value + "]"
                $(queryStr).prop("checked", true);
            }
            if (debugSetValue) console.log(queryStr)
        }
    } else {
        //Dropdown and dropdownMulti
        getEById(id).val(value)
    }
}
// const removeParenthesis = (str) => (str.includes("()")) ? str.substring(0, str.length - 2) : str

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
                if (row[column] === undefined) {
                    console.error("Column [", column, "] in filter_columns not found in", column_name, "(Relationships Screen)")
                    // } else {
                    //     console.log("Column [", column, "] in filter_columns found in", column_name, "(Relationships Screen)");
                }
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

    const constraintsValues = triggers.map((trigger) => getValueOfEById(trigger))
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
    const lastSelected = getValueOfEById(column_name)
    // console.log("Selected of", column_name, "is", lastSelected)
    reloadDataToDropdown2(column_name, dataSource, [lastSelected * 1])
}
const onChangeGetSelectedObject2 = (listener) => {
    const { listen_to_fields, listen_to_tables } = listener
    const listen_to_field = listen_to_fields[0]
    const listen_to_table = listen_to_tables[0]
    const selectedId = getValueOfEById(listen_to_field)
    // const selectedId = getEById(listen_to_field).val()

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
            if (debugListener) console.log("Set value of", column_name, "to", theValue)
            // getEById(column_name).val(theValue)
            setValueOfEById(column_name, theValue)
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

const onChangeDropdown2Expression = (listener) => {
    // const debugListener = true
    if (debugListener) console.log("Expression", listener)
    const { expression, column_name } = listener
    let expression1 = expression

    const vars = getAllVariablesFromExpression(expression)
    for (let i = 0; i < vars.length; i++) {
        const varName = vars[i]
        if (['Math', 'round', 'ceil', 'trunc', 'toDateString'].includes(varName)) continue
        let varValue = getEById(varName).val() || 0
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
    if (debugListener) console.log(column_name, '=', expression1, '=', result)
    getEById(column_name).val(result)
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
                case "expression":
                    onChangeDropdown2Expression(listener)
                    break
                default:
                    console.error("Unknown listen_action", listen_action, "of", name);
                    break;
            }
        }
    }
}

const reloadDataToDropdown2 = (id, dataSource, selected) => {
    const control_type = getControlTypeOfE(id)

    // console.log("reloadDataToDropdown2", id, control_type, dataSource.length, selected)
    if (dataSource === undefined) return;
    getEById(id).empty()

    let options = []
    dataSource = filterDropdown2(id, dataSource)
    // console.log("Loading dataSource after filterDropdown2", id, selected, dataSource.length)

    if (control_type === 'dropdown') {
        for (let i = 0; i < dataSource.length; i++) {
            let item = dataSource[i]
            selectedStr = (dataSource.length === 1) ? 'selected' : (selected.includes(item.id) ? "selected" : "")
            option = "<option value='" + item.id + "' title='" + item.description + "' " + selectedStr + " >"
            option += item.name || "Nameless #" + item.id
            option += "</option>"
            options.push(option)
        }
        options.unshift("<option value=''></option>")
        getEById(id).append(options)
        // console.log("Appended", id, 'with options has', options.length, 'items')

        getEById(id).select2({
            placeholder: "Please select..."
            // , allowClear: true //<<This make a serious bug when user clear and re-add a multiple dropdown, it created a null element
            , templateResult: select2FormatState
        });
    } else if (control_type == "radio_or_checkbox") {
        // const control = getEById(id)
        // const isMultiple = control[0].hasAttribute("multiple")
        const isMultiple = getIsMultipleOfE(id)
        const radio_or_checkbox = isMultiple ? "checkbox" : "radio"
        const control_name = isMultiple ? (id + "[]") : id
        const colSpan = getColSpanOfE(id)
        for (let i = 0; i < dataSource.length; i++) {
            let item = dataSource[i];
            selectedStr = (dataSource.length === 1) ? 'checked' : (selected.includes(item['id']) ? "checked" : "")
            // console.log(item)
            const title = item['description'] + " (#" + item['id'] + ")"
            option = '<div class="items-center bg-white-50 flex align-center ' + colSpan + '">'
            option += '<label class="truncate" title="' + title + '">'
            option += '<input type="' + radio_or_checkbox + '" name="' + control_name + '" value="' + item['id'] + '" ' + selectedStr + '>'
            option += " " + item['name']
            option += '</label>'
            option += '</div>'
            options.push(option)
        }
        getEById(id).append(options)
    } else {
        console.error("Unknown control_type", control_type)
    }
}

const RadioOrCheckbox = ({ id, name, className, multiple, span }) => {
    name = multiple ? name + "[]" : name
    multipleStr = multiple ? 'multiple' : ''

    const colSpan = "col-span-" + span

    let render = ""
    render += "<div "
    render += "id='" + id + "' "
    render += "name='" + name + "' "
    render += "onChange='onChangeDropdown2(\"" + name + "\")' "
    render += " " + multipleStr + " "
    render += "controlType='radio_or_checkbox' "
    render += "colSpan='" + colSpan + "' "
    render += "class='" + className + "' "
    render += ">"
    render += "</div>"
    return render
}

const Dropdown2 = ({ id, name, className, multipleStr }) => {
    let render = ""
    render += "<select "
    render += "id='" + id + "' "
    render += "name='" + name + "' "
    render += "onChange='onChangeDropdown2(\"" + name + "\")' "
    render += " " + multipleStr + " "
    render += "controlType='dropdown' "
    render += "class='" + className + "' "
    render += ">"
    render += "</select>"

    return render
}

const documentReadyDropdown2 = ({ id, selectedJson, table }) => {
    // selectedJson = '{!! $selected !!}'
    selectedJson = selectedJson.replace(/\\/g, '\\\\') //<< Replace \ to \\ EG. ["App\Models\Qaqc_mir"] to ["App\\Models\\Qaqc_mir"]
    selectedJson = JSON.parse(selectedJson)
    // table = "{{$table}}"
    dataSourceDropdown = k[table];
    if (dataSourceDropdown === undefined) console.error("key {{$table}} not found in k[]");
    reloadDataToDropdown2(id, dataSourceDropdown, selectedJson)

    $(document).ready(() => {
        listenersOfDropdown2.forEach((listener) => {
            if (listener.triggers.includes(id) && listener.listen_action === 'reduce') {
                // console.log("I am a trigger of reduce, I have to trigger myself when form load [id]", )
                getEById(id).trigger('change')
            }
        })
    })
}