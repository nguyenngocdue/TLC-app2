let k = {},
    listenersOfDropdown2 = [],
    filtersOfDropdown2 = [],
    debugListener = false,
    debugFlow = false
const makeIdForNumber = (n) => '#' + String(n).padStart(6, '0').substring(0, 3) + '.' + String(n).padStart(6, '0').substring(3)
const makeId = (n) => (isNaN(n) ? '' : makeIdForNumber(n))
const select2FormatState = (state) => !state.id ? state.text : $(
    `<div class="flex justify-between px-1"><span>${state.text
    }</span><pre>   </pre><span>${isNaN(state.id) ? state.id : makeId(state.id)
    }</span></div>`
)
const getEById = (id) => $("[id='" + id + "']")
const dumbIncludes2 = (array, item) => { for (let i = 0; i < array.length; i++) { if (array[i] == item) return true } return false }
const getIsMultipleOfE = (id) => (getEById(id)[0]) ? getEById(id)[0].hasAttribute('multiple') : false
const getControlTypeOfE = (id) => getEById(id).attr('controlType')
const getAllowClear = (id) => !!(getEById(id).attr('allowClear') === 'true')
const getColSpanOfE = (id) => getEById(id).attr('colSpan')
const getReadOnlyOfE = (id) => getEById(id).attr('readOnly')
const getAllVariablesFromExpression = (str) => {
    const regex = new RegExp('[a-zA-Z_]+[a-zA-Z0-9]*', 'gm'), result = []
    let m
    while ((m = regex.exec(str)) !== null) {
        // This is necessary to avoid infinite loops with zero-width matches
        if (m.index === regex.lastIndex) regex.lastIndex++
        m.forEach((match) => result.push(match))
    }
    // console.log(result)
    return result
}
const getSecondsFromTime = (hms) => {
    var a = hms.split(':') // split it at the colons
    switch (a.length) {
        case 1: return +a[0]
        case 2: return +a[0] * 60 * 60 + +a[1] * 60 // HH:MM
        case 3: return +a[0] * 60 * 60 + +a[1] * 60 + +a[2]
    }
}
const getDaysFromDateSlash = (dmy) => moment(dmy, 'DD/MM/YYYY').diff(moment('1970-01-01'), 'days')
const getDaysFromDateDash = (dmy) => moment(dmy, 'YYYY-MM-DD').diff(moment('1970-01-01'), 'days')
const getValueOfEById = (id) => {
    const isMultipleOfE = getIsMultipleOfE(id)
    const controlType = getControlTypeOfE(id)
    // console.log(id, isMultipleOfE, controlType)
    if (controlType === 'radio_or_checkbox') {
        const control = isMultipleOfE ? 'checkbox' : 'radio'
        const name = isMultipleOfE ? id + '[]' : id
        const controlName = 'input:' + control + "[name='" + name + "']:checked"
        let value = []
        if (isMultipleOfE) {
            const checkboxes = $(controlName)
            for (let i = 0; i < checkboxes.length; i++) {
                value.push(checkboxes[i].value)
                // console.log(checkboxes[i].value)
            }
        } else {
            value.push($(controlName).val())
        }
        // console.log(controlName, value)
        return value
    }
    return getEById(id).val()
}
const setValueOfEById = (id, value) => {
    const debugSetValue = false
    const isMultipleOfE = getIsMultipleOfE(id)
    const controlType = getControlTypeOfE(id)
    // console.log(id, isMultipleOfE, controlType)
    let queryStr = ''
    if (controlType === 'radio_or_checkbox') {
        // console.log(id, value)
        if (isMultipleOfE) {
            // $("input:checkbox[name='dropdownMonitors()[]']").prop("checked",false)
            queryStr = "input:checkbox[name='" + id + "[]']"
            $(queryStr).prop('checked', false)
            if (debugSetValue) console.log('Unchecked all', queryStr)
            value.forEach((id_id) => {
                queryStr =
                    "input:checkbox[name='" + id + "[]'][value=" + id_id + ']'
                $(queryStr).prop('checked', true)
                if (debugSetValue) console.log('Checking', queryStr)
            })
        } else {
            // $("input:radio[name='assignee_1'][value=765]").prop("checked",true)
            if (value == null) {
                queryStr = "input:radio[name='" + id + "']"
                $(queryStr).prop('checked', false)
            } else {
                queryStr = "input:radio[name='" + id + "'][value=" + value + ']'
                $(queryStr).prop('checked', true)
            }
            if (debugSetValue) console.log(queryStr)
        }
    } else {
        //Dropdown and dropdownMulti
        getEById(id).val(value)
    }
}
// const removeParenthesis = (str) => (str.includes("()")) ? str.substring(0, str.length - 2) : str

const convertStrToNumber = (varValue) => {
    const debugListener = false
    let type = "normal_number"
    if (varValue.includes(",")) type = "number_with_commas"
    else if (isNaN(varValue)) type = "datetime_string"

    // console.log(varValue, isNaN(varValue))
    if (varValue) {
        switch (type) {
            case "normal_number": break
            case "number_with_commas":
                varValue = (varValue + '').replace(/\,/g, '') * 1  //<<Remove all "," as the thousand separator
                break
            case "datetime_string":
                const includedHour = varValue.includes(':')
                const includedDateSlash = varValue.includes('/')
                const includedDateDash = varValue.includes('-')
                if (includedHour && includedDateSlash) {
                    const datetime = varValue.split(' ')
                    const date = datetime[0]
                    const time = datetime[1]
                    varValue = getDaysFromDateSlash(date) * 24 * 3600 + getSecondsFromTime(time)
                } else {
                    if (includedHour) {
                        varValue = getSecondsFromTime(varValue)
                    } else if (includedDateSlash) {
                        varValue = getDaysFromDateSlash(varValue) * 24 * 3600
                    } else if (includedDateDash) {
                        varValue = getDaysFromDateDash(varValue) * 24 * 3600
                    }
                }
                break
            default: console.log("Unknown how to convert string to value: " + varValue)
        }

        if (debugListener) console.log(varName, varValue)
        return varValue
    }
}

const filterDropdown2 = (column_name, dataSource) => {
    // const filtersOfDropdown2 = filtersOfDropdown2s[lineType]
    // console.log(filtersOfDropdown2s, filtersOfDropdown2, column_name, lineType)
    if (filtersOfDropdown2[column_name] !== undefined) {
        const { filter_columns, filter_values } =
            filtersOfDropdown2[column_name]
        //Filter by filter_columns and filter_values
        for (let i = 0; i < filter_columns.length; i++) {
            const column = filter_columns[i]
            const value = filter_values[i]
            dataSource.forEach((row) => {
                if (row[column] === undefined) {
                    console.error('Column [', column, '] in filter_columns not found in', column_name, '(Relationships Screen)')
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
    if (debugListener) console.log('Reduce listener', listener)
    const { column_name, table_name, listen_to_attrs, triggers, attrs_to_compare, } = listener
    let dataSource = k[table_name]
    if (debugListener) console.log('dataSource in k of', table_name, dataSource)

    const constraintsValues = triggers.map((trigger) => getValueOfEById(trigger))
    if (debugListener) console.log(triggers, constraintsValues)

    for (let i = 0; i < triggers.length; i++) {
        const value = constraintsValues[i]
        if (!value) continue
        // console.log("value of trigger[", i, "]", value)

        const column = listen_to_attrs[i]
        if (column === undefined) console.log('The column to look up [', column, '] is not found in ...')
        if (debugListener) console.log('Applying', column, value, 'to', table_name)

        dataSource = dataSource.filter((row) => {
            let result = null
            // console.log("Row:", row, "column:", column, "row[column]", row[column])
            if (Array.isArray(row[column])) {
                // console.log("Check if", value, "is in", row[column], result ? "YES" : "")
                result = dumbIncludes2(row[column], value)
            } else {
                result = (row[column] == value)
                // console.log("Check if", value, "is =", row[column], result ? "YES" : "")
            }
            if (debugListener) console.log('Result of reduce filter', row[column], value, result)
            return result
        })
    }

    if (debugListener) console.log('DataSource AFTER reduce', dataSource)
    // console.log('onChangeDropdown2Reduce')
    let lastSelected = getValueOfEById(column_name)
    // console.log(column_name, lastSelected)
    if (undefined === lastSelected) lastSelected = []
    if (!Array.isArray(lastSelected)) lastSelected = [lastSelected]
    // console.log(column_name, lastSelected, dataSource)

    // const lastSelected1 = []
    // //Try to delete the data if the new dataSource doesn't have it
    // for (let i = 0; i < dataSource.length; i++) {
    //     for (let j = 0; j < lastSelected.length; j++) {
    //         // console.log("comparing", dataSource[i]['id'], lastSelected[j])
    //         if (dataSource[i]['id'] == lastSelected[j]) {
    //             lastSelected1.push(lastSelected[j]);
    //         }
    //     }
    // }
    // console.log("lastSelected", lastSelected)
    // console.log("lastSelected1", lastSelected1)

    // const lastSelected1 = (Array.isArray(lastSelected) && lastSelected.length == 0) ? [] : lastSelected
    // console.log("Selected of", column_name, "is", lastSelected)
    // console.log(attrs_to_compare)
    const allowClear = getAllowClear(column_name)
    // console.log(column_name, allowClear, false)
    reloadDataToDropdown2(column_name, attrs_to_compare, dataSource, lastSelected, allowClear)

    // console.log("Set to ", column_name, lastSelected1)
    // setValueOfEById(column_name, lastSelected1)
    // getEById(column_name).trigger('change')

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

const onChangeGetSelectedObject2_1 = (listener, index = 0) => {
    const { listen_to_fields, listen_to_tables, listen_to_attrs } = listener
    const listen_to_field = listen_to_fields[index]
    const listen_to_table = listen_to_tables[index]
    const listen_to_attr = listen_to_attrs[index] || "id"
    const selectedId = getValueOfEById(listen_to_field)

    const table = k[listen_to_table]
    const selectedObject = table.find((i) => i[listen_to_attr] == selectedId)

    return selectedObject
}

const onChangeDropdown2Assign = (listener) => {
    // const debugListener = true
    if (debugListener) console.log('Assign', listener)
    const { column_name, listen_to_attrs } = listener
    const selectedObject = onChangeGetSelectedObject2(listener)
    let listen_to_attr = listen_to_attrs[0]

    //This section allows {currency_pair1_id, currency_pair2_id} to be lookup the value on the form to make it column
    if (listen_to_attr[0] === "{" && listen_to_attr[listen_to_attr.length - 1] === "}") {
        listen_to_attr = listen_to_attr.slice(1, -1)
        // console.log(listen_to_attr)
        listen_to_attr = getValueOfEById(listen_to_attr)
    }

    // const listen_to_attr = removeParenthesis(listen_to_attrs[0])
    if (debugListener) console.log('Selected Object:', selectedObject, ' - listen_to_attr:', listen_to_attr)
    if (selectedObject !== undefined) {
        const theValue = selectedObject[listen_to_attr]
        if (theValue !== undefined) {
            // const column_name1 = removeParenthesis(column_name)
            if (debugListener) console.log('Set value of', column_name, 'to', theValue)
            // getEById(column_name).val(theValue)
            setValueOfEById(column_name, theValue)
            getEById(column_name).trigger('change')
        } else {
            console.error('Column', listen_to_attr, 'not found in', column_name, '(Listeners Screen)')
        }
    }
}
const onChangeDropdown2Dot = (listener) => {
    // const debugListener = true
    if (debugListener) console.log('Dot', listener)
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
        if (debugListener)
            console.log('Dotting', column_name, 'with value', theValue)
    }
}

const onChangeDropdown2DateOffset = (listener) => {
    if (debugListener) console.log('Date Offset', listener)
    const selectedObject = onChangeGetSelectedObject2(listener)

    const { listen_to_attrs, column_name } = listener
    const listen_to_attr = listen_to_attrs[0]
    // console.log(listen_to_attr, column_name, selectedObject)
    if (selectedObject !== undefined) {
        const theValue = selectedObject[listen_to_attr]
        if (debugListener) console.log(theValue)

        const theValueDate = moment().add(theValue, 'days').format('DD/MM/YYYY HH:mm')
        if (debugListener) console.log(theValueDate)

        getEById(column_name).val(theValueDate)
        getEById(column_name).trigger('change')
        if (debugListener) console.log('Date Offset', column_name, 'with value', theValueDate)
    }
}

const onChangeDropdown2Expression = (listener) => {
    // const debugListener = true
    if (debugListener) console.log('Expression', listener)
    const { expression, column_name } = listener
    let expression1 = expression

    const vars = getAllVariablesFromExpression(expression)
    for (let i = 0; i < vars.length; i++) {
        const varName = vars[i]
        if (['Math', 'round', 'ceil', 'trunc', 'toDateString', 'toFixed'].includes(varName)) continue
        let varValue = (getEById(varName).val() || 0) + '' //<< toString
        varValue = convertStrToNumber(varValue)

        if (debugListener) console.log(varName, '=', varValue)
        expression1 = expression1.replace(varName, varValue)
    }
    const result = eval(expression1)
    if (debugListener) console.log(column_name, '=', expression1, '=', result)
    getEById(column_name).val(result)
    getEById(column_name).trigger('change')
}
const onChangeDropdown2AjaxRequestScalar = (listener) => {
    // const debugListener = true
    if (debugListener) console.log('AjaxRequestScalar', listener)
    const { triggers, expression: url } = listener
    const { ajax_response_attribute, ajax_form_attributes, ajax_item_attributes, ajax_default_values, } = listener

    let enoughParams = true
    const data = {}
    const missingParams = []
    for (let i = 0; i < triggers.length; i++) {
        let value = getEById(triggers[i]).val()
        if (value === null || value === '' || value === undefined) {
            enoughParams = false
            missingParams.push(triggers[i])
        }
        data[triggers[i]] = value
    }
    if (enoughParams) {
        if (debugListener)
            console.log('Sending AjaxRequest with data:', data, url)
        $.ajax({
            url,
            data,
            success: (response) => {
                let value = -1
                if (debugListener) console.log('Response', response)
                const hits_0 = response[ajax_response_attribute][0]
                for (let i = 0; i < ajax_form_attributes.length; i++) {
                    if (hits_0 === undefined) {
                        value = ajax_default_values[i]
                        if (debugListener)
                            console.log(
                                'Response empty',
                                ajax_response_attribute,
                                '- assigning default value',
                                ajax_default_values[i]
                            )
                    } else if (hits_0[ajax_item_attributes[i]] === undefined) {
                        value = ajax_default_values[i]
                        if (debugListener)
                            console.log(
                                'Requested column',
                                ajax_item_attributes[i],
                                'not found, assigning default value',
                                ajax_default_values[i]
                            )
                    } else {
                        value = hits_0[ajax_item_attributes[i]]
                    }
                    const toBeAssigned = ajax_form_attributes[i]
                    if (debugListener)
                        console.log(
                            'Assigning',
                            toBeAssigned,
                            'with value',
                            value
                        )
                    getEById(toBeAssigned).val(value)
                    getEById(toBeAssigned).trigger('change')
                }
            },
            error: (response) => console.error(response),
        })
    } else {
        if (debugListener)
            console.log(
                'Sending AjaxRequest cancelled as not enough parameters',
                missingParams
            )
    }
}

const onChangeSetTableColumn = (listener, triggerIndex) => {
    // console.log("set_table_column", listener, tableObject)
    const { column_name, listen_to_attrs, columns_to_set } = listener
    const table01Name = tableObjectColName[column_name]?.['name']
    if (undefined === table01Name) return //<< During create new, the table is not rendered yet

    const listen_to_attr = listen_to_attrs[triggerIndex]
    const column_to_set = columns_to_set[triggerIndex]

    const selectedObject = onChangeGetSelectedObject2_1(listener, triggerIndex)
    // console.log("Selected Obj", selectedObject)
    const selectedAttr = selectedObject[listen_to_attr]
    // console.log("Setting column value for ", table01Name, selectedObject, column_name)

    const length = getAllRows(table01Name).length

    for (let i = 0; i < length; i++) {
        const id = table01Name + "[" + column_to_set + "][" + i + "]" //table01[my_file][0]
        getEById(id).val(selectedAttr)
        getEById(id).trigger('change', length)
    }
}

const onChangeNumberToWords = (listener) => {
    // console.log("onChangeNumberToWords", listener)
    const { triggers, column_name } = listener
    const number = getValueOfEById(triggers[0])
    let numberWords = ""
    if (number) {
        numberWords = numberToWords(number)
    }

    const currency = getValueOfEById(triggers[1])
    let currencyWords = ""
    if (currency) {
        const currencyObject = k["act_currencies"].find(i => i['id'] === currency * 1)
        // console.log(currencyObject)
        currencyWords = (currencyObject)?.description
    }
    // console.log(numberWords)
    getEById(column_name).val(numberWords + " " + currencyWords)
    getEById(column_name).trigger('change')
}

const onChangeDropdown2 = (name) => {
    // const debugFlow = true
    // console.log("onChangeDropdown2", name)
    // console.log(listenersOfDropdown2)
    for (let i = 0; i < listenersOfDropdown2.length; i++) {
        let listener = listenersOfDropdown2[i]
        const { triggers, listen_action, column_name } = listener

        // console.log(listen_action, name, triggers)
        if (debugFlow) console.log(name, "-->", column_name, listen_action)
        for (let i = 0; i < triggers.length; i++) {
            // if (triggers.includes(name)) {
            if (name == triggers[i]) {
                // console.log("listen_action", listen_action)
                switch (listen_action) {
                    case 'reduce':
                        onChangeDropdown2Reduce(listener)
                        break
                    case 'assign':
                        onChangeDropdown2Assign(listener)
                        break
                    case 'dot':
                        onChangeDropdown2Dot(listener)
                        break
                    case 'date_offset':
                        onChangeDropdown2DateOffset(listener)
                        break
                    case 'expression':
                        onChangeDropdown2Expression(listener)
                        break
                    case 'ajax_request_scalar':
                        onChangeDropdown2AjaxRequestScalar(listener)
                        break
                    case 'trigger_change_some_lines':
                        //Do nothing, this is an action of table
                        break
                    case 'set_table_column':
                        onChangeSetTableColumn(listener, i)
                        break
                    case 'number_to_words':
                        onChangeNumberToWords(listener)
                        break

                    default:
                        console.error('Unknown listen_action', listen_action, 'of', name)
                        break
                }
            }
        }
    }
}

const reloadDataToDropdown2 = (id, attr_to_compare = 'id', dataSource, selected, allowClear = false) => {
    // const debugListener = true
    const control_type = getControlTypeOfE(id)
    if (debugListener) console.log(id, attr_to_compare)
    if (debugListener) console.log('reloadDataToDropdown2', id, control_type, dataSource.length, selected)
    if (dataSource === undefined) return
    getEById(id).empty()

    let options = []
    dataSource = filterDropdown2(id, dataSource)
    if (debugListener) console.log('Loading dataSource after filterDropdown2', id, selected, dataSource.length)
    // console.log(selected)

    if (control_type === 'dropdown') {
        for (let i = 0; i < dataSource.length; i++) {
            let item = dataSource[i]
            selectedStr =
                dataSource.length === 1 ? 'selected' : dumbIncludes2(selected, item.id) ? 'selected' : ''
            // console.log(id, selected, item.id, selectedStr)
            const title = item.description || (isNaN(item.id) ? item.id : makeId(item.id))
            option = "<option value='" + item.id + "' title='" + title + "' " + selectedStr + ' >'
            option += item.name || 'Nameless #' + item.id
            option += '</option>'
            options.push(option)
        }
        options.unshift("<option value=''></option>")
        getEById(id).append(options)
        if (debugListener) console.log('Appended', id, 'with options has', options.length, 'items')

        getEById(id).select2({
            placeholder: 'Please select...',
            allowClear,
            // , allowClear: true //<<This make a serious bug when user clear and re-add a multiple dropdown, it created a null element
            templateResult: select2FormatState,
            // , disabled: true
        })
        if (dataSource.length === 1) getEById(id).trigger('change')
    } else if (control_type == 'radio_or_checkbox') {
        // const control = getEById(id)
        // const isMultiple = control[0].hasAttribute("multiple")
        const isMultiple = getIsMultipleOfE(id)
        const radio_or_checkbox = isMultiple ? 'checkbox' : 'radio'
        const control_name = isMultiple ? id + '[]' : id
        const colSpan = getColSpanOfE(id)
        const readOnly = getReadOnlyOfE(id)
        // console.log(attr_to_compare)
        for (let i = 0; i < dataSource.length; i++) {
            let item = dataSource[i]
            const itemId = item[attr_to_compare]
            selectedStr = dataSource.length === 1 ? 'checked' : dumbIncludes2(selected, itemId) ? 'checked' : ''
            // console.log(selected, itemId, selectedStr)
            // console.log(readOnly)
            readonly = readOnly ? 'onclick="return false;"' : ''
            // console.log(item)
            const title = item['description'] + ' (#' + itemId + ')'
            const bgColor = item['bgColor'] || ''
            option =
                '<div class="items-center bg-white-50 flex align-center rounded-md ' +
                bgColor +
                ' ' +
                colSpan +
                ' ' +
                '" ' +
                'item_name="' +
                item['name'] +
                '" ' +
                'item_description="' +
                item['description'] +
                '" ' +
                '">'
            const cursor = item['disabled']
                ? 'cursor-not-allowed'
                : 'cursor-pointer'
            const inputBg = item['disabled'] ? 'bg-gray-300' : ''
            option +=
                '<label class="truncate px-1 ' +
                cursor +
                ' rounded-md hover:bg-gray-100 w-full h-full" title="' +
                title +
                '">'
            option += "<div class='flex align-middle'>"
            option +=
                '<input ' +
                (item['disabled'] ? 'disabled ' : '') +
                readonly +
                ' ' +
                'class="w-3.5 h-3.5 mr-1 mt-0.5 ' +
                inputBg +
                ' ' +
                cursor +
                '" ' +
                'type="' +
                radio_or_checkbox +
                '" ' +
                'name="' +
                control_name +
                '" ' +
                'value="' +
                itemId +
                '" ' +
                selectedStr +
                ' ' +
                '>'
            if (item['avatar'])
                option +=
                    ' ' +
                    '<img class="w-10 h-10 mr-1 rounded" src="' +
                    item['avatar'] +
                    '" />'
            option += '<div>'
            option += ' ' + item['name']
            if (item['subtitle']) option += '<br/>' + item['subtitle']
            option += '</div>'
            option += '</div>'
            option += '</label>'
            option += '</div>'
            options.push(option)
        }
        getEById(id).append(options)
    } else if (control_type === 'draggable_event') {
        // const control = getEById(id)
        // const isMultiple = control[0].hasAttribute("multiple")
        // const isMultiple = getIsMultipleOfE(id)
        // const radio_or_checkbox = isMultiple ? 'checkbox' : 'radio'
        // const control_name = isMultiple ? id + '[]' : id
        const colSpan = getColSpanOfE(id)
        const readOnly = getReadOnlyOfE(id)
        // console.log(attr_to_compare)
        // console.log('Here ne Canh', dataSource)
        for (let i = 0; i < dataSource.length; i++) {
            let item = dataSource[i]
            const itemId = item[attr_to_compare]
            selectedStr = dataSource.length === 1 ? 'checked' : dumbIncludes2(selected, itemId) ? 'checked' : ''
            // console.log(selected, itemId, selectedStr)
            // console.log(readOnly)
            readonly = readOnly ? 'onclick="return false;"' : ''
            classEvent = readOnly ? '' : 'fc-event'
            // console.log(item)
            const title = item['description'] + ' (#' + itemId + ')'
            const bgColor = item['bgColor'] || ''
            option =
                `<div class="${classEvent} fc-h-event fc-daygrid-event fc-daygrid-block-event cursor-pointer my-1 px-2 py-0.5` +
                bgColor +
                ' ' +
                colSpan +
                ' ' +
                '" ' +
                'item_name="' +
                item['name'] +
                '" ' +
                'item_description="' +
                item['description'] +
                '" ' +
                '">'
            // const cursor = item['disabled']
            //     ? 'cursor-not-allowed'
            //     : 'cursor-pointer'
            // const inputBg = item['disabled'] ? 'bg-gray-300' : ''
            // option +=
            //     '<label class="truncate px-1 ' +
            //     cursor +
            //     ' rounded-md hover:bg-gray-100 w-full h-full" title="' +
            //     title +
            //     '">'
            // option += "<div class='flex align-middle'>"
            // option +=
            //     '<input ' +
            //     (item['disabled'] ? 'disabled ' : '') +
            //     readonly +
            //     ' ' +
            //     'class="w-3.5 h-3.5 mr-1 mt-0.5 ' +
            //     inputBg +
            //     ' ' +
            //     cursor +
            //     '" ' +
            //     'type="' +
            //     radio_or_checkbox +
            //     '" ' +
            //     'name="' +
            //     control_name +
            //     '" ' +
            //     'value="' +
            //     itemId +
            //     '" ' +
            //     selectedStr +
            //     ' ' +
            //     '>'
            option += '<div id="' + itemId + '" class="fc-event-main">'
            option += ' ' + item['name']
            if (item['subtitle']) option += '<br/>' + item['subtitle']
            option += '</div>'
            // option += '</div>'
            // option += '</label>'
            option += '</div>'
            options.push(option)
        }
        options.push(
            "<p><input type='checkbox' class='hidden' id='drop-remove' /></p>"
        )
        getEById(id).append(options)
    } else {
        console.error('Unknown control_type', control_type)
    }
}

const documentReadyDropdown2 = (params) => {
    // console.log(params)
    const { id, selectedJson, table, allowClear = false, action } = params

    // selectedJson = '{!! $selected !!}'
    const selectedJson1 = selectedJson.replace(/\\/g, '\\\\') //<< Replace \ to \\ EG. ["App\Models\Qaqc_mir"] to ["App\\Models\\Qaqc_mir"]
    const selectedArray = JSON.parse(selectedJson1)
    // console.log(selectedArray)
    // table = "{{$table}}"
    dataSourceDropdown = k[table]
    if (dataSourceDropdown === undefined)
        console.error('key ' + table + ' not found in k[]')
    let attr_to_compare = 'id'
    // for (let i = 0; i < listenersOfDropdown2.length; i++) {
    //     if (listenersOfDropdown2[i].column_name === id) {
    //         console.log(listenersOfDropdown2[i], attrs_to_compare[0])
    //         attr_to_compare = listenersOfDropdown2[i].attrs_to_compare[0]
    //         break
    //     }
    // }
    // console.log(id, listenersOfDropdown2, attr_to_compare, dataSourceDropdown)
    // console.log(id, attr_to_compare, dataSourceDropdown, selectedJson)
    reloadDataToDropdown2(id, attr_to_compare, dataSourceDropdown, selectedArray, allowClear)

    $(document).ready(() => {
        if (Array.isArray(listenersOfDropdown2)) {
            listenersOfDropdown2.forEach((listener) => {
                const list =
                    action === 'create'
                        ? ['reduce', 'assign']
                        : ['reduce' /* 'assign'*/] //<< without assign, keep value from DB, otherwise it will be overwritten every time the form is loaded
                if (listener.triggers.includes(id) && list.includes(listener.listen_action)) {
                    // console.log("I am a trigger of reduce/assign, I have to trigger myself when form load [id]",)
                    getEById(id).trigger('change')
                }
            })
        } else {
            // console.log("There is no registered listeners of dropdown2")
        }
    })
}
