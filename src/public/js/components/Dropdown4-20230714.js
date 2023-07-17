let runOnce = {}
const showErrorMessage = (response) => {
    const { message, exception } = response.responseJSON
    toastr.error(message, exception)
    console.log(response)
}
//Similar to includes, this will be checking both numbers and strings
const dumbIncludes4 = (item, array) => {
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
                result = dumbIncludes4(value, row[column])
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

const onChangeDropdown4Assign = (listener, table01Name, rowIndex, batchLength = 1) => {
    // const debugListener = true
    if (debugListener) console.log("Assign", listener)
    const { column_name, listen_to_attrs } = listener
    const selectedObject = onChangeGetSelectedObject4(listener, table01Name, rowIndex)
    let listen_to_attr = listen_to_attrs[0]

    //This section allows {currency_pair1_id, currency_pair2_id} to be lookup the value on the form to make it column
    if (listen_to_attr[0] === "{" && listen_to_attr[listen_to_attr.length - 1] === "}") {
        listen_to_attr = listen_to_attr.slice(1, -1)
        // console.log(listen_to_attr)
        const idToLookUp = makeIdFrom(table01Name, listen_to_attr, rowIndex)
        listen_to_attr = getValueOfEById(idToLookUp)
    }

    // const listen_to_attr = removeParenthesis(listen_to_attrs[0])
    if (debugListener) console.log("Selected Object:", selectedObject, " - listen_to_attr:", listen_to_attr)
    if (selectedObject !== undefined) {
        const theValue = selectedObject[listen_to_attr]
        const id = makeIdFrom(table01Name, column_name, rowIndex)
        if (theValue !== undefined) {
            // const column_name1 = removeParenthesis(id)
            if (debugListener) console.log("Set value of", id, "to", theValue)
            getEById(id).val(theValue)
            getEById(id).trigger('change', batchLength)
        }
        else {
            console.error("Column", listen_to_attr, 'not found in', id, "(Listeners Screen)")
        }
    }
}

const dotQueue = {}

const onChangeDropdown4Dot = (listener, table01Name, rowIndex, batchLength = 1) => {
    // const debugListener = true
    if (debugListener) console.log("Dot", listener, batchLength)

    const { listen_to_attrs, column_name } = listener
    const listen_to_attr = listen_to_attrs[0]


    if (dotQueue[column_name] === undefined) dotQueue[column_name] = {}
    if (dotQueue[column_name]['data'] === undefined) dotQueue[column_name]['data'] = []
    dotQueue[column_name]['data'].push(rowIndex)

    const data = dotQueue[column_name]['data']
    if (data.length >= batchLength) {
        delete (dotQueue[column_name])
        let actualBatchLength = 0
        for (let i = 0; i < data.length; i++) {
            rowIndex = data[i]
            const selectedObject = onChangeGetSelectedObject4(listener, table01Name, rowIndex)
            if (debugListener) console.log(selectedObject, listen_to_attr)
            if (selectedObject !== undefined) actualBatchLength++
        }
        for (let i = 0; i < data.length; i++) {
            rowIndex = data[i]
            const selectedObject = onChangeGetSelectedObject4(listener, table01Name, rowIndex)
            // Unknown error
            if (selectedObject !== undefined) {
                const theValue = selectedObject[listen_to_attr]
                // console.log(theValue)
                const id = makeIdFrom(table01Name, column_name, rowIndex)
                if (debugListener) console.log(id, theValue)


                getEById(id).val(theValue)
                getEById(id).trigger('change', actualBatchLength)
                if (debugListener) console.log("Dotting", id, "with value", theValue)
            }
        }
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

const onChangeDropdown4Expression = (listener, table01Name, rowIndex, batchLength = 1) => {
    // const debugListener = true
    if (debugListener) console.log("Expression", listener, table01Name, rowIndex)
    const { expression, column_name } = listener
    let expression1 = expression

    const vars = getAllVariablesFromExpression(expression)
    for (let i = 0; i < vars.length; i++) {
        const varName = vars[i]
        if (['Math', 'round', 'ceil', 'trunc', 'toDateString', 'toFixed'].includes(varName)) continue
        const varNameFull = makeIdFrom(table01Name, varName, rowIndex)
        let varValue = (getEById(varNameFull).val() || 0) + '' //<< toString
        varValue = convertStrToNumber(varValue)

        if (debugListener) console.log(varName, "=", varValue)
        expression1 = expression1.replace(varName, varValue)
    }
    const result = eval(expression1)
    if (debugListener) console.log(column_name, '=', expression1, result)
    const id = makeIdFrom(table01Name, column_name, rowIndex)
    getEById(id).val(result)
    getEById(id).trigger('change', batchLength)
}

const ajaxQueueSelect = {}

const onChangeDropdown4AjaxRequestScalar = (listener, table01Name, rowIndex, batchLength = 1) => {
    // const debugListener = true
    if (debugListener) console.log("AjaxRequestScalar", listener)
    // console.log(batchLength)
    const { triggers, expression: url } = listener
    const { ajax_response_attribute, ajax_form_attributes, ajax_item_attributes, ajax_default_values } = listener

    let enoughParams = true
    const data0 = {}
    const missingParams = []
    for (let i = 0; i < triggers.length; i++) {
        const name = makeIdFrom(table01Name, triggers[i], rowIndex)
        let value = getEById(name).val()
        if (value === null || value === '' || value === undefined) {
            enoughParams = false
            missingParams.push(name)
        }
        data0[triggers[i]] = value
    }
    if (enoughParams) {
        if (debugListener) console.log("Sending AjaxRequest with data0:", data0, url)

        if (ajaxQueueSelect[url] == undefined) ajaxQueueSelect[url] = {}
        if (ajaxQueueSelect[url]['data'] == undefined) ajaxQueueSelect[url]['data'] = []
        if (ajaxQueueSelect[url]['rowIndex'] == undefined) ajaxQueueSelect[url]['rowIndex'] = []
        ajaxQueueSelect[url]['data'].push(data0)
        ajaxQueueSelect[url]['rowIndex'].push(rowIndex)
        // console.log(ajaxQueueSelect[url]['rowIndex'])

        for (let i = 0; i < ajax_form_attributes.length; i++) {
            const id = makeIdFrom(table01Name, ajax_form_attributes[i], rowIndex)
            getEById(id).hide()
        }

        if (ajaxQueueSelect[url]['data'].length >= batchLength) {
            const data = ajaxQueueSelect[url]['data']
            // console.log("Sending AjaxRequest with data:", data, url)
            const rowIndexCache = ajaxQueueSelect[url]['rowIndex']
            delete (ajaxQueueSelect[url])
            $.ajax({
                type: 'POST',
                url, data: { lines: data },
                success: (response) => {
                    let value = -1
                    if (debugListener) console.log("Response", response)
                    const hits = response['hits']
                    // console.log(response, hits)
                    for (let lineIndex = 0; lineIndex < batchLength; lineIndex++) {
                        const hit = hits[lineIndex]
                        for (let i = 0; i < ajax_form_attributes.length; i++) {
                            const form_att = ajax_form_attributes[i]
                            const item_att = ajax_item_attributes[i]
                            const defaultValue = ajax_default_values[i]
                            const id = makeIdFrom(table01Name, form_att, (batchLength == 1 ? rowIndex : rowIndexCache[lineIndex]))
                            // console.log(id)
                            // console.log(hit, form_att, item_att)
                            if (hit[item_att] == undefined) {
                                value = defaultValue
                                if (debugListener) console.log("Requested column", item_att, 'not found in response - assigning default value', defaultValue)
                            } else {
                                value = hit[item_att]
                            }
                            if (debugListener) console.log("Assigning", id, "with value", value)
                            getEById(id).val(value)
                            getEById(id).trigger('change', batchLength)

                            getEById(id).show()
                        }
                    }
                },
                error: (response) => showErrorMessage(response)
            })
        }
    } else {
        if (debugListener) console.log("Sending AjaxRequest cancelled as not enough parameters", missingParams)
    }
}

const triggerChangeQueue = {}

const onChangeDropdown4TriggerChangeAllLines = (listener, table01Name, rowIndex, batchLength = 1) => {
    // const debugListener = true
    if (debugListener) console.log("TriggerChangeAllLines", listener)
    const { column_name } = listener

    if (triggerChangeQueue[column_name] == undefined) triggerChangeQueue[column_name] = {}
    if (triggerChangeQueue[column_name]['data'] == undefined) triggerChangeQueue[column_name]['data'] = []
    triggerChangeQueue[column_name]['data'].push(1)

    const data = triggerChangeQueue[column_name]['data']
    // console.log(data.length, batchLength)
    if (data.length >= batchLength) {
        delete (triggerChangeQueue[column_name]['data'])
        if (batchLength == 1) {
            const rows = getAllRows(table01Name)
            batchLength = rows.length
            for (let i = 0; i < batchLength; i++) {
                if (i === rowIndex) continue
                const id = makeIdFrom(table01Name, column_name, i, batchLength)
                // setTimeout(() =>
                getEById(id).trigger('change', batchLength - 1)
                // , 1000)
            }
        } else {
            for (let i = 0; i < batchLength; i++) {
                const id = makeIdFrom(table01Name, column_name, i, batchLength)
                // setTimeout(() =>
                getEById(id).trigger('change', batchLength)
                // , 1000)
            }
        }

    }
}

const ajaxQueueUpdate = {}

const onChangeDropdown4 = ({ name, table01Name, rowIndex, lineType, saveOnChange, batchLength = 1 }) => {
    // console.log("onChangeDropdown4", name, table01Name, rowIndex, saveOnChange, lineType)
    // console.log("listenersOfDropdown4s", listenersOfDropdown4s, table01Name)
    // console.log("onChangeDropdown4", name, batchLength)
    const fieldName = getFieldNameInTable01FormatJS(name, table01Name)
    const { tableName } = tableObject[table01Name]
    const url = '/api/v1/entity/' + tableName + '_updateShort'
    if (saveOnChange) {
        // console.log("in save on change", batchLength)
        // console.log("saveOnChange", tableName, fieldName)
        const lineId = makeIdFrom(table01Name, 'id', rowIndex)
        const id = getEById(lineId).val()
        const value = getEById(name).val()
        const data0 = { id, value, fieldName }

        if (ajaxQueueUpdate[url] == undefined) ajaxQueueUpdate[url] = {}
        if (ajaxQueueUpdate[url][fieldName] == undefined) ajaxQueueUpdate[url][fieldName] = {}
        if (ajaxQueueUpdate[url][fieldName]['data'] == undefined) ajaxQueueUpdate[url][fieldName]['data'] = []
        if (ajaxQueueUpdate[url][fieldName]['rowIndex'] == undefined) ajaxQueueUpdate[url][fieldName]['rowIndex'] = []
        ajaxQueueUpdate[url][fieldName]['data'].push(data0)
        ajaxQueueUpdate[url][fieldName]['rowIndex'].push(rowIndex)
    }
    if (saveOnChange) {
        if (ajaxQueueUpdate[url][fieldName]['data'].length >= batchLength) {
            const data = ajaxQueueUpdate[url][fieldName]['data']
            const rowIndexes = ajaxQueueUpdate[url][fieldName]['rowIndex']
            // console.log("Sending AjaxRequest Update ", fieldName, " with data:", data, url, batchLength)
            delete (ajaxQueueUpdate[url][fieldName])
            // console.log("Send request", url)
            $.ajax({
                type: 'POST',
                url,
                data: { lines: data },
                success: (response) => {
                    if (debugListener) console.log("Response", response)
                    // console.log("Returned from server, batchLength", batchLength)
                    // console.log("Execute listeners")
                    // const hits = response['hits']
                    for (let i = 0; i < rowIndexes.length; i++) {
                        onChangeFull({ fieldName, table01Name, rowIndex: rowIndexes[i], lineType, batchLength, name })
                    }
                },
                error: (response) => showErrorMessage(response)
            })
        }
    } else {
        onChangeFull({ fieldName, table01Name, rowIndex, lineType, batchLength, name })
    }
}

const onChangeFull = ({ fieldName, table01Name, rowIndex, lineType, batchLength = 1, name }) => {
    // const debugFlow = true
    // console.log({ fieldName, table01Name, rowIndex, lineType, batchLength, name })
    const listenersOfDropdown4 = listenersOfDropdown4s[table01Name]
    for (let i = 0; i < listenersOfDropdown4.length; i++) {
        let listener = listenersOfDropdown4[i]
        const { triggers, listen_action, column_name } = listener
        // console.log(triggers, listen_action, name, fieldName, table01Name, rowIndex)
        if (triggers.includes(fieldName)) {
            // console.log("listen_action", listen_action)
            if (debugFlow) console.log(name, "-->", column_name, listen_action, table01Name + "_" + rowIndex, batchLength)
            switch (listen_action) {
                case "reduce":
                    onChangeDropdown4Reduce(listener, table01Name, rowIndex, lineType)
                    break
                case "assign":
                    onChangeDropdown4Assign(listener, table01Name, rowIndex, batchLength)
                    break
                case "dot":
                    onChangeDropdown4Dot(listener, table01Name, rowIndex, batchLength)
                    break
                case "date_offset":
                    onChangeDropdown4DateOffset(listener, table01Name, rowIndex)
                    break
                case "expression":
                    onChangeDropdown4Expression(listener, table01Name, rowIndex, batchLength)
                    break
                case "ajax_request_scalar":
                    onChangeDropdown4AjaxRequestScalar(listener, table01Name, rowIndex, batchLength)
                    break
                case "trigger_change_all_lines":
                    onChangeDropdown4TriggerChangeAllLines(listener, table01Name, rowIndex, batchLength)
                    break
                default:
                    console.error("Unknown listen_action", listen_action, "of", name);
                    break;
            }
        }
    }
}

const reloadDataToDropdown4 = (id, dataSource, table01Name, selected) => {
    // console.log("reloadDataToDropdown4", id, dataSource.length, table01Name, selected)
    // console.log(table01Name, id, selected)
    if (dataSource === undefined) return;
    const theDropdown = getEById(id)
    theDropdown.empty()

    let options = ["<option value=''></option>"]
    // console.log("Loading dataSource for", id, selected, dataSource)
    dataSource = filterDropdown4(id, dataSource, table01Name)

    for (let i = 0; i < dataSource.length; i++) {
        let item = dataSource[i]
        selectedStr = (dataSource.length === 1) ? 'selected' : (dumbIncludes4(item.id, selected) ? "selected" : "")
        // console.log("During making option list", item.id, item.name, "================================", selectedStr)
        const title = item.description || makeId(item.id)
        option = "<option value='" + item.id + "' title='" + title + "' " + selectedStr + " >"
        option += item.name || "Nameless #" + item.id
        option += "</option>"
        options.push(option)
    }
    theDropdown.append(options)
    // console.log("Appended", id, 'with options has', options.length, 'items')
    theDropdown.select2({
        placeholder: "Please select"
        // , allowClear: true //<<This make a serious bug when user clear and re-add a multiple dropdown, it created a null element
        , templateResult: select2FormatState
    });
    if (dataSource.length === 1) theDropdown.trigger('change')
}

const documentReadyDropdown4 = ({ id, table01Name, selectedJson, table, batchLength = 1 }) => {
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
    //This will load all line without reduce
    reloadDataToDropdown4(id, dataSourceDropdown, table01Name, selectedJson)
    $(document).ready(() => {
        listenersOfDropdown4s[table01Name].forEach((listener) => {
            const fieldName = getFieldNameInTable01FormatJS(id, table01Name)
            if (listener.triggers.includes(fieldName) && listener.listen_action === 'reduce') {
                // console.log("I am a trigger of reduce, I have to trigger myself when form load ", id)
                getEById(id).trigger('change', batchLength)
            }
        })
    })
}