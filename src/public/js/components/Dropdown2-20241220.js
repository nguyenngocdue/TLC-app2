const dumbIncludes2 = (array, item) => {
    for (let i = 0; i < array.length; i++) {
        if (array[i] == item) return true
    }
    return false
}
const smartFilter2 = (dataSource, column, operator, value) => {
    return dataSource.filter((row) => {
        let result = null
        // console.log("Row:", row, "column:", column, "row[column]", row[column])
        switch (operator || '=') {
            case '=':
                if (Array.isArray(row[column])) {
                    result = dumbIncludes2(row[column], value)
                } else {
                    result = row[column] == value
                }
                return result
            case '!=':
                if (Array.isArray(row[column])) {
                    result = !dumbIncludes2(row[column], value)
                } else {
                    result = row[column] != value
                }
                return result
            case 'IN_UNION':
                // console.log(row[column], column, operator, value)
                if (Array.isArray(row[column])) {
                    if (Array.isArray(value)) {
                        result = value.some((v) => dumbIncludes2(row[column], v))
                    } else {
                        result = dumbIncludes2(row[column], value)
                    }
                } else {
                    console.error('REDUCE_UNION is only for array, but ', column, 'is not an array')
                }
                return result
            case 'IN_INTERSECT':
                if (Array.isArray(row[column])) {
                    if (Array.isArray(value)) {
                        result = value.every((v) => dumbIncludes2(row[column], v))
                    } else {
                        result = dumbIncludes2(row[column], value)
                    }
                } else {
                    console.error(
                        'REDUCE_INTERSECT is only for array, but ',
                        column,
                        'is not an array',
                    )
                }
                return result
            default:
                console.error('Unknown operator', operator)
                return false
        }
    })
}
const filterDropdown2 = (column_name, dataSource) => {
    // const filtersOfDropdown2 = filtersOfDropdown2s[lineType]
    // console.log(filtersOfDropdown2s, filtersOfDropdown2, column_name, lineType)
    if (filtersOfDropdown2[column_name] !== undefined) {
        const { filter_columns, filter_operator, filter_values } = filtersOfDropdown2[column_name]
        //Filter by filter_columns and filter_values
        for (let i = 0; i < filter_columns.length; i++) {
            const column = filter_columns[i]
            const value = filter_values[i]
            dataSource.forEach((row) => {
                if (row[column] === undefined) {
                    console.error(
                        'Column [',
                        column,
                        '] in filter_columns not found in',
                        column_name,
                        '(Relationships Screen)',
                    )
                    // } else {
                    //     console.log("Column [", column, "] in filter_columns found in", column_name, "(Relationships Screen)");
                }
            })
            dataSource = smartFilter2(dataSource, column, filter_operator, value)
        }
    }
    return dataSource
}

const onChangeDropdown2Reduce = (listener, is_union) => {
    // const debugListener = true
    if (debugListener) console.log('Reduce listener', listener)
    const { column_name, table_name, listen_to_attrs, triggers, attrs_to_compare } = listener
    let dataSource = k[table_name]
    if (debugListener) console.log('dataSource in k of', table_name, dataSource)

    const selectedValues = triggers.map((trigger) => getValueOfEById(trigger))
    if (debugListener) console.log(triggers, selectedValues)

    for (let i = 0; i < triggers.length; i++) {
        const selectedValue = selectedValues[i]
        if (!selectedValue) continue
        // console.log("selectedValue of trigger[", i, "]", selectedValue)

        // console.log(triggers[i], Array.isArray(selectedValue) ? 'Array' : 'Not Array')
        const column = listen_to_attrs[i]
        if (column === undefined)
            console.log('The column to look up [', column, '] is not found in ...')
        if (debugListener)
            console.log('Applying', column, selectedValue, 'to', table_name, dataSource)

        if (Array.isArray(selectedValue)) {
            if (is_union) {
                dataSource = smartFilter2(dataSource, column, 'IN_UNION', selectedValue)
                // console.log('dataSource after union', column_name, dataSource)
            } else {
                dataSource = smartFilter2(dataSource, column, 'IN_INTERSECT', selectedValue)
            }
        } else {
            dataSource = smartFilter2(dataSource, column, '=', selectedValue)
        }
    }

    // if (debugListener) console.log('DataSource AFTER reduce', dataSource)
    // console.log('onChangeDropdown2Reduce')
    let lastSelected = getValueOfEById(column_name)
    // console.log(column_name, lastSelected)
    if (undefined === lastSelected) lastSelected = []
    if (!Array.isArray(lastSelected)) lastSelected = [lastSelected]
    // console.log(column_name, lastSelected, dataSource)

    const allowClear = getAllowClear(column_name)
    const letUserChooseWhenOneItem = getLetUserChooseWhenOneItem(column_name)
    // console.log(column_name, allowClear, false)
    reloadDataToDropdown2(
        column_name,
        attrs_to_compare,
        dataSource,
        lastSelected,
        letUserChooseWhenOneItem,
        allowClear,
    )
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
    const listen_to_attr = listen_to_attrs[index] || 'id'
    const selectedId = getValueOfEById(listen_to_field)

    const table = k[listen_to_table]
    const selectedObject = table.find((i) => i[listen_to_attr] == selectedId)

    return selectedObject
}

const onChangeDropdown2Assign = (listener, onLoad) => {
    // const debugListener = true
    if (debugListener) console.log('Assign', listener)
    if (onLoad) return //<< If monitor1 or assignee1 is filled, this will prevent the discipline1 to overwrite
    const { column_name, listen_to_attrs } = listener
    const selectedObject = onChangeGetSelectedObject2(listener)
    let listen_to_attr = listen_to_attrs[0]

    //This section allows {currency_pair1_id, currency_pair2_id} to be lookup the value on the form to make it column
    if (listen_to_attr[0] === '{' && listen_to_attr[listen_to_attr.length - 1] === '}') {
        listen_to_attr = listen_to_attr.slice(1, -1)
        // console.log(listen_to_attr)
        listen_to_attr = getValueOfEById(listen_to_attr)
    }

    // const listen_to_attr = removeParenthesis(listen_to_attrs[0])
    if (debugListener)
        console.log('Selected Object:', selectedObject, ' - listen_to_attr:', listen_to_attr)
    if (selectedObject !== undefined) {
        const theValue = selectedObject[listen_to_attr]
        if (theValue !== undefined) {
            // const column_name1 = removeParenthesis(column_name)
            if (debugListener) console.log('Set value of', column_name, 'to', theValue)
            // getEById(column_name).val(theValue)
            setValueOfEById(column_name, theValue)
            getEById(column_name).trigger('change')
        } else {
            console.error(
                'Column',
                listen_to_attr,
                'not found in',
                column_name,
                '(Listeners Screen)',
            )
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
        // console.log(superProps['props']["_" + column_name], column_name)

        //suffix here has not been implemented
        const control = superProps['props']['_' + column_name]?.['control']

        switch (control) {
            case 'picker_date':
                newFlatPickrDate(column_name).setDate(theValue)
                break
            //Haven't tested yet:
            // case "picker_time":
            //     newFlatPickrTime(column_name).setDate(theValue)
            //     break
            // case "picker_datetime":
            //     newFlatPickrDateTime(column_name).setDate(theValue)
            //     break
            default:
                getEById(column_name).val(theValue)
                break
        }

        getEById(column_name).trigger('change')
        if (debugListener) console.log('Dotting', column_name, 'with value', theValue)
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

        const twelveHoursLater = new Date(new Date().getTime() + theValue * 24 * 60 * 60 * 1000)
        newFlatPickrDateTime(column_name).setDate(twelveHoursLater)
        flatpickrHandleChange(column_name, [twelveHoursLater])

        getEById(column_name).trigger('change')
        if (debugListener) console.log('Date Offset', column_name, 'with value', twelveHoursLater)
    }
}

const onChangeDropdown2Expression = (listener) => {
    const debugListener = !true
    if (debugListener) console.log('Expression', listener)
    const { expression, column_name } = listener
    let expression1 = expression

    const vars = getAllVariablesFromExpression(expression)
    for (let i = 0; i < vars.length; i++) {
        const varName = vars[i]
        if (
            [
                'Math',
                'abs',
                'floor',
                'round',
                'ceil',
                'trunc',
                'toDateString',
                'toFixed',
                'min',
                'max',
                'Infinity',
            ].includes(varName)
        )
            continue
        let varValue = (getEById(varName).val() || 0) + '' //<< toString
        varValue = convertStrToNumber(varValue)

        if (debugListener) console.log(varName, '=', varValue)
        expression1 = expression1.replace(varName, varValue)
    }
    const result = eval(expression1)
    if (debugListener) console.log(column_name, ':(', expression1, ', result:', result, ')')
    const datetime_controls = superProps.datetime_controls
    const controlNames = Object.keys(datetime_controls)
    if (controlNames.includes(column_name)) {
        switch (datetime_controls[column_name]) {
            case 'picker_date':
                newFlatPickrDate(column_name).setDate(new Date(result * 1000))
                break
            default:
                console.log(
                    'Unsupport datetime control',
                    datetime_controls[column_name],
                    'for',
                    column_name,
                )
                break
        }
    } else {
        getEById(column_name).val(result)
        getEById(column_name).trigger('change')
    }
}
const onChangeDropdown2AjaxRequestScalar = (listener) => {
    // const debugListener = true
    if (debugListener) console.log('AjaxRequestScalar', listener)
    const { triggers, expression: url } = listener
    const {
        ajax_response_attribute,
        ajax_form_attributes,
        ajax_item_attributes,
        ajax_default_values,
    } = listener

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
        if (debugListener) console.log('Sending AjaxRequest with data:', data, url)
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
                                ajax_default_values[i],
                            )
                    } else if (hits_0[ajax_item_attributes[i]] === undefined) {
                        value = ajax_default_values[i]
                        if (debugListener)
                            console.log(
                                'Requested column',
                                ajax_item_attributes[i],
                                'not found, assigning default value',
                                ajax_default_values[i],
                            )
                    } else {
                        value = hits_0[ajax_item_attributes[i]]
                    }
                    const toBeAssigned = ajax_form_attributes[i]
                    if (debugListener) console.log('Assigning', toBeAssigned, 'with value', value)
                    getEById(toBeAssigned).val(value)
                    getEById(toBeAssigned).trigger('change')
                }
            },
            error: (response) => console.error(response),
        })
    } else {
        if (debugListener)
            console.log('Sending AjaxRequest cancelled as not enough parameters', missingParams)
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

    const batchLength = getAllRows(table01Name).length

    for (let i = 0; i < batchLength; i++) {
        const id = table01Name + '[' + column_to_set + '][' + i + ']' //table01[my_file][0]
        getEById(id).val(selectedAttr)
        getEById(id).trigger('change', { batchLength })
    }
}

const onChangeNumberToWords = (listener) => {
    // console.log('onChangeNumberToWords', listener)
    const { triggers, column_name, expression: lang } = listener
    const number = getValueOfEById(triggers[0])
    let numberWords = ''
    if (number) {
        //Remove all comma
        let cleanedString = number.replace(/,/g, '')

        numberWords = lang == 'vn' ? numberToWordsVn(cleanedString) : numberToWords(cleanedString)
    }

    const currency = getValueOfEById(triggers[1])
    let currencyWords = ''
    if (currency) {
        const currencyObject = k['act_currencies'].find((i) => i['id'] === currency * 1)
        // console.log(currencyObject)
        currencyWords = currencyObject?.description
    }
    // console.log(numberWords)
    getEById(column_name).val(numberWords + ' ' + currencyWords)
    getEById(column_name).trigger('change')
}

const onChangeDropdown2CountSelectedValues = (listener, name) => {
    const { column_name } = listener
    const count = getEById(name).val().length

    // console.log(listener, name, count)
    getEById(column_name).val(count)
    getEById(column_name).trigger('change')
}

const onChangeDropdown2 = ({ name, dropdownParams = {} }) => {
    // const debugFlow = true
    // console.log("onChangeDropdown2", name)
    // console.log(listenersOfDropdown2)
    const { onLoad = false } = dropdownParams
    // console.log(name, onLoad)
    for (let i = 0; i < listenersOfDropdown2.length; i++) {
        let listener = listenersOfDropdown2[i]
        const { triggers, listen_action, column_name } = listener

        // console.log(listen_action, name, triggers)
        if (debugFlow) console.log(name, '-->', column_name, listen_action)
        for (let i = 0; i < triggers.length; i++) {
            // if (triggers.includes(name)) {
            if (name == triggers[i]) {
                // console.log("listen_action", listen_action)
                switch (listen_action) {
                    case 'reduce':
                        onChangeDropdown2Reduce(listener, false)
                        break
                    case 'reduce_union':
                        onChangeDropdown2Reduce(listener, true)
                        break
                    case 'assign':
                        onChangeDropdown2Assign(listener, onLoad)
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
                    case 'trigger_change_all_lines':
                    case 'trigger_change_all_lines_except_current':
                        // Do nothing, this is an action of table
                        break
                    case 'set_table_column':
                        onChangeSetTableColumn(listener, i)
                        break
                    case 'number_to_words':
                        onChangeNumberToWords(listener)
                        break
                    case 'count_selected_values':
                        onChangeDropdown2CountSelectedValues(listener, name)
                        break
                    default:
                        console.error('Unknown listen_action', listen_action, 'of', name)
                        break
                }
            }
        }
    }
}

const reloadDataToDropdown2 = (
    id,
    attr_to_compare = 'id',
    dataSource,
    selected,
    letUserChooseWhenOneItem = false,
    allowClear = false,
) => {
    // const debugListener = true
    const control_type = getControlTypeOfE(id)
    if (debugListener) console.log(id, attr_to_compare)
    if (debugListener)
        console.log('reloadDataToDropdown2', id, control_type, dataSource.length, selected)
    if (dataSource === undefined) return
    getEById(id).empty()

    let options = []
    dataSource = filterDropdown2(id, dataSource)
    if (dataSource?.[0]?.name) {
        // console.log(id, dataSource)
        dataSource = dataSource
            .filter((item) => {
                const isNotResigned = !item.name?.includes('(RESIGNED)')
                if (selected) {
                    const isInSelected = selected.includes(item.id)
                    return isNotResigned || isInSelected
                } else {
                    return isNotResigned
                }
            })
            .sort((a, b) => a.name?.localeCompare(b.name))
    }
    if (dataSource?.[0]?.order_no) {
        dataSource = dataSource.sort((a, b) => {
            // console.log(a, a.order_no, b.order_no)
            return a.order_no - b.order_no
        })
    }

    if (debugListener)
        console.log('Loading dataSource after filterDropdown2', id, selected, dataSource.length)
    // console.log(selected)

    if (control_type === 'dropdown') {
        for (let i = 0; i < dataSource.length; i++) {
            let item = dataSource[i]
            if (letUserChooseWhenOneItem) {
                selectedStr = dumbIncludes2(selected, item.id) ? 'selected' : ''
            } else {
                selectedStr =
                    dataSource.length === 1
                        ? 'selected'
                        : dumbIncludes2(selected, item.id)
                        ? 'selected'
                        : ''
            }
            // console.log(id, selected, item.id, selectedStr)
            const title = item.employeeid || item.description || '' //  || (isNaN(item.id) ? item.id : makeId(item.id))
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
            templateResult: select2FormatOption,
            // templateSelection: select2FormatSelected,
            escapeMarkup: (markup) => markup, // Disable escaping of HTML
            // , disabled: true
            matcher: select2Matcher,
        })
        if (dataSource.length === 1) {
            // console.log("Changes when only one item in data source", id)
            getEById(id).trigger('change')
        }
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
            // selectedStr = (dataSource.length === 1) ? 'checked' : (dumbIncludes2(selected, itemId) ? 'checked' : '')
            if (letUserChooseWhenOneItem) {
                selectedStr = dumbIncludes2(selected, item.id) ? 'checked' : ''
            } else {
                selectedStr =
                    dataSource.length === 1
                        ? 'checked'
                        : dumbIncludes2(selected, item.id)
                        ? 'checked'
                        : ''
            }
            readonly = readOnly ? 'onclick="return false;"' : ''
            disabled = item['disabled'] ? 'disabled' : ''
            const title = item['description'] + ' (#' + itemId + ')'
            const bgColor = item['bgColor'] || ''
            const classNames1 = `items-center bg-white-50 flex align-center rounded-md ${bgColor} ${colSpan}`
            const cursor = item['disabled'] ? 'cursor-not-allowed' : 'cursor-pointer'
            const inputBg = item['disabled'] ? 'bg-gray-300' : ''
            const classNames2 = `truncate px-1 ${cursor} rounded-md hover:bg-gray-100 w-full h-full`
            const classNames3 = `w-3.5 h-3.5 mr-1 mt-0.5 ${inputBg} ${cursor}`
            const avatar = item['avatar']
                ? `<img class="w-10 h-10 mr-1 rounded" src="${item['avatar']}" />`
                : ''
            const subtitle = item['subtitle'] ? `<br/>${item['subtitle']}` : ''

            option = `<div class="${classNames1}" item_name="${item['name']}" item_description="${item['description']}">
                        <label class="${classNames2}" title="${title}">
                            <div class='flex align-middle'>
                                <input ${disabled} ${readonly} class="${classNames3}" type="${radio_or_checkbox}" name="${control_name}" value="${itemId}" ${selectedStr}>
                                ${avatar}
                                <div>
                                    ${item['name']}
                                    ${subtitle}
                                </div>
                            </div>
                        </label>
                    </div>`
            options.push(option)
        }
        getEById(id).append(options)
    } else if (control_type === 'draggable_event') {
        const colSpan = getColSpanOfE(id)
        const readOnly = getReadOnlyOfE(id)
        for (let i = 0; i < dataSource.length; i++) {
            let item = dataSource[i]
            const itemId = item[attr_to_compare]
            readonly = readOnly ? 'onclick="return false;"' : ''
            classEvent = readOnly ? '' : 'fc-event'
            const bgColor = item['bgColor'] || ''
            const classNames1 = `relative w-full fc-event-main123 bg-sky-500 text-white cursor-pointer rounded mt-0.5 px-2 py-0.5`
            const subtitle = item['subtitle'] ? '<br/>' + item['subtitle'] : ''
            option = ''

            option += `<div task-id="${itemId}"`
            option += ` onMouseOver="$('.sub-task-of-${itemId}').show()"`
            option += ` onMouseOut="$('.sub-task-of-${itemId}').hide()"`
            option += ` class="${classNames1}"`
            option += ` component="draggable-dropdown2">`
            option += ` ${item['name']}`
            option += ` ${subtitle}`

            option += `<div class="absolute w-3/4 right-0 top-0 bg-gray-100 p-1 rounded z-50 sub-task-of-${itemId}" style="display:none">`
            const { get_children_sub_tasks } = item
            if (get_children_sub_tasks.length > 0) {
                for (let j = 0; j < get_children_sub_tasks.length; j++) {
                    const subTaskItem = get_children_sub_tasks[j]
                    option += `<div class="${classEvent} ${bgColor} ${colSpan} fc-h-event fc-daygrid-event fc-daygrid-block-event cursor-pointer my-0.5 px-2 py-0.5 "
                            item_name="${item['name']}" item_description="${item['description']}">`
                    option += `<div id="${itemId}" sub-task-id="${subTaskItem['id']}" class="fc-event-main456 whitespace-normal">${subTaskItem['name']}</div>`
                    option += '</div>'
                }
            } else {
                option += `<div class="text-center text-red-400">No sub-task found.</div>`
            }
            option += '</div>'

            option += '</div>'
            options.push(option)
        }
        options.push("<p><input type='checkbox' class='hidden' id='drop-remove' /></p>")
        getEById(id).append(options)
    } else {
        console.error('Unknown control_type', control_type)
    }
}

const documentReadyDropdown2 = (params) => {
    // console.log(params)
    const { id, selectedJson, table, allowClear = false, action, letUserChooseWhenOneItem } = params

    const selectedJson1 = selectedJson.replace(/\\/g, '\\\\') //<< Replace \ to \\ EG. ["App\Models\Qaqc_mir"] to ["App\\Models\\Qaqc_mir"]
    const selectedArray = JSON.parse(selectedJson1)
    // console.log(selectedArray)
    dataSourceDropdown = k[table]
    if (dataSourceDropdown === undefined) console.error('key ' + table + ' not found in k[]')
    let attr_to_compare = 'id'
    reloadDataToDropdown2(
        id,
        attr_to_compare,
        dataSourceDropdown,
        selectedArray,
        letUserChooseWhenOneItem,
        allowClear,
    )

    $(document).ready(() => {
        if (Array.isArray(listenersOfDropdown2)) {
            listenersOfDropdown2.forEach((listener) => {
                const list =
                    action === 'create'
                        ? ['reduce', 'reduce_union', 'assign']
                        : ['reduce', 'reduce_union' /* 'assign'*/] //<< without assign, keep value from DB, otherwise it will be overwritten every time the form is loaded
                if (listener.triggers.includes(id) && list.includes(listener.listen_action)) {
                    // console.log("I am a trigger of ", listener.listen_action, ", I have to trigger myself when form load [", id, "]",)
                    getEById(id).trigger('change', { onLoad: true })
                }
            })
        } else {
            // console.log("There is no registered listeners of dropdown2")
        }
    })
}
