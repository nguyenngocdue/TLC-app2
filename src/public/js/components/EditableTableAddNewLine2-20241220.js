const removeEmptinessLine = (tableId) => $('#' + tableId + '_emptiness').remove()

const findParentIdFieldName = (tableId, key_value_as = 'value_as_parent_id') => {
    const { columns } = tableObject[tableId]
    for (let i = 0; i < columns.length; i++) {
        const keys = Object.keys(columns[i])
        // console.log(keys)
        for (let j = 0; j < keys.length; j++) {
            const key = keys[j]
            if (key === key_value_as && columns[i][key]) {
                // console.log(key, columns[i][key], columns[i]['dataIndex'])
                return columns[i]['dataIndex']
            }
        }
    }
    return 'NOT_FOUND_' + key_value_as
}

const ajaxAddLineQueue = {}

const getDefaultValueOfSetTableColumnListener = () => {
    const result = {}
    listenersOfDropdown2.forEach((listener) => {
        if (listener.listen_action === 'set_table_column') {
            const { listen_to_fields } = listener
            listen_to_fields.forEach((field) => {
                const selectedId = getEById(field).val()
                result[field] = selectedId
            })
        }
    })
    // console.log(k, result)
    return result
    // return {
    //     rate_exchange_month_id: 1,
    //     counter_currency_id: 1,
    // }
}

const addANewLine = (params) => {
    const {
        tableId,
        valuesOfOrigin = {},
        isDuplicatedOrAddFromList = false,
        batchLength = 1,
    } = params
    const { tableName, dateTimeControls = [] } = tableObject[tableId]
    // console.log(params, tableName,)
    const orderNoValue = getMaxValueOfAColumn(tableId, '[order_no]') + table_order_no_step

    const currentUser = getEById('currentUserId').val()

    const parentIdFieldName = findParentIdFieldName(tableId, 'value_as_parent_id')
    const parentId = getEById('entityParentId').val()

    const parentTypeFieldName = findParentIdFieldName(tableId, 'value_as_parent_type')
    const parentType = getEById('entityParentType').val()
    // console.log(parentIdFieldName)

    //Check if edit sequence
    if (parentIdFieldName === 'prod_sequence_id') {
        const a = tableObject[tableId].tableName
        const b = tableObject[tableId].eloquentFn
        if (a === 'prod_runs') {
            if (b === 'getProdRuns') valuesOfOrigin['is_rework'] = 0
            if (b === 'getProdRunsRework1') valuesOfOrigin['is_rework'] = 1
            if (b === 'getProdRunsRework2') valuesOfOrigin['is_rework'] = 2
            if (b === 'getProdRunsSubCon') valuesOfOrigin['is_rework'] = 10
            if (b === 'getProdRunsSubConPartTime') valuesOfOrigin['is_rework'] = 11
        }
    }

    const data0 = {
        owner_id: currentUser,
        [parentIdFieldName]: parentId,
        [parentTypeFieldName]: parentType,
        project_id: getEById('entityProjectId').val(),
        sub_project_id: getEById('entitySubProjectId').val(),
        // rate_exchange_month_id: getEById('entityCurrencyMonth').val(),
        // counter_currency_id: getEById('entityCurrencyExpected').val(),
        order_no: orderNoValue,

        ...valuesOfOrigin,
        ...getDefaultValueOfSetTableColumnListener(),
    }
    // console.log(data0)

    const btnAddANewLineId = 'btnAddANewLine_' + tableId
    const btnAddFromAListId = 'btnAddFromAList_' + tableId
    const spinId = 'iconSpin_' + tableId
    getEById(btnAddANewLineId).hide()
    getEById(btnAddFromAListId).hide()
    getEById(spinId).show()

    const url = '/api/v1/entity/' + tableName + '_storeEmpty'

    if (ajaxAddLineQueue[url] == undefined) ajaxAddLineQueue[url] = {}
    if (ajaxAddLineQueue[url]['data'] == undefined) ajaxAddLineQueue[url]['data'] = []
    // if (ajaxAddLineQueue[url]['rowIndex'] == undefined) ajaxAddLineQueue[url]['rowIndex'] = []
    ajaxAddLineQueue[url]['data'].push(data0)
    // ajaxAddLineQueue[url]['rowIndex'].push(rowIndex)

    if (ajaxAddLineQueue[url]['data'].length >= batchLength) {
        const data = ajaxAddLineQueue[url]['data']

        delete ajaxAddLineQueue[url]
        // console.log('Inserting', data)

        const FORMAT = {
            picker_datetime: 'DD/MM/YYYY hh:mm',
            picker_date: 'DD/MM/YYYY',
            picker_time: 'hh:mm',

            picker_month: 'MM/YYYY',
            picker_year: 'YYYY',
        }

        $.ajax({
            type: 'POST',
            url,
            data: { lines: data },
            success: (response) => {
                // console.log(url, "is done")
                // console.log(response)
                // console.log(dateTimeControls)
                for (let i = 0; i < batchLength; i++) {
                    const valuesOfOrigin = response['hits'][i]
                    addANewLineFull({
                        tableId,
                        valuesOfOrigin,
                        isDuplicatedOrAddFromList,
                        batchLength,
                        tableName,
                    })
                }
                getEById(btnAddANewLineId).show()
                getEById(btnAddFromAListId).show()
                getEById(spinId).hide()
            },
            error: (response) => {
                const { exception, message } = response.responseJSON
                toastr.error(message, exception)
                console.error(response)
            },
        })
    }
}

const addANewLineFull = (params) => {
    const { tableId, tableName, isDuplicatedOrAddFromList, batchLength = 1 } = params
    let { valuesOfOrigin } = params //<< Incase of duplicate, this is the value of the original line
    // console.log("valuesOfOrigin: ", valuesOfOrigin)
    const insertedId = valuesOfOrigin['id']
    // console.log('addANewLine', tableId, insertedId)
    const { columns, showNo, showNoR, tableDebugJs, isOrderable } = tableObject[tableId]
    // console.log("ADD LINE TO", params, tableDebugJs, isOrderable)

    const table = document.getElementById(tableId)
    // console.log(table)
    const row = table.insertRow(-1)
    const tbody = table.getElementsByTagName('tbody')[0]
    tbody.appendChild(row) //<<Make sure the newly inserted row will be above the tfoot
    removeEmptinessLine(tableId) //<< Must remove after insertRow, otherwise it will insert into 2nd thead
    const newRowNo = getAllRows(tableId).length

    row.classList.add('bg-lime-200')
    let fingerPrint = ''
    if (showNo) {
        //<< Ignore No. column
        const noCell = row.insertCell()
        noCell.classList = 'px-1 py-1 dark:border-gray-600 border-r text-center'
        noCell.innerHTML = newRowNo
    }
    const toDoAfterAddedDropdown4 = []
    const toDoAfterAddedDropdown4ReadOnly = []
    const newRowIndex = getAllRows(tableId).length - 1 //exclude itself
    // console.log("newRowIndex", newRowIndex, getAllRows(tableId))
    // console.log("Start the column")
    columns.forEach((column) => {
        if (column['hidden'] == true) return
        // console.log(column['dataIndex'])
        let renderer = 'newCell'
        let orderNoValue = 0
        if (column['properties']) {
            Object.keys(column['properties']).forEach((key) => {
                column[key] = column['properties'][key]
            })
            delete column['properties']
            // console.log(column)
        }
        // console.log(column['dataIndex'], column['saveOnChange'])
        const saveOnChange = column['saveOnChange'] ? 1 : 0

        const id = tableId + '[' + column['dataIndex'] + '][' + newRowIndex + ']'
        if (column['dataIndex'] === 'action') {
            fingerPrint = getMaxValueOfAColumn(tableId, '[finger_print]') + table_order_no_step

            const fingerPrintName = tableId + '[finger_print][' + newRowIndex + ']'
            const fingerPrintInput =
                '<input readonly class="w-10 bg-gray-300" name="' +
                fingerPrintName +
                '" value="' +
                fingerPrint +
                '" type=' +
                (tableDebugJs ? 'text' : 'hidden') +
                ' />'

            const destroyName = tableId + '[DESTROY_THIS_LINE][' + newRowIndex + ']'
            const destroyInput =
                '<input readonly class="w-10 bg-gray-300" name="' +
                destroyName +
                '" type=' +
                (tableDebugJs ? 'text' : 'hidden') +
                ' />'

            const paramString =
                "{tableId: '" +
                tableId +
                "', control: this, fingerPrint:" +
                fingerPrint +
                ', nameIndex:' +
                newRowIndex +
                '}'

            const btnUp = `<button value="${tableId}" onClick="moveUpEditableTable(${paramString})" type="button" class="px-1.5 py-1 border-2 border-gray-200 inline-block font-medium text-xs leading-tight uppercase rounded focus:ring-0 transition duration-150 ease-in-out bg-gray-200 text-gray-700 shadow-md hover:bg-gray-300 hover:shadow-lg focus:bg-gray-300 focus:shadow-lg focus:outline-none active:bg-gray-400 active:shadow-lg" ><i class="fa fa-arrow-up"></i></button>`
            const btnDown = `<button value="${tableId}" onClick="moveDownEditableTable(${paramString})" type="button" class="px-1.5 py-1 border-2 border-gray-200 inline-block font-medium text-xs leading-tight uppercase rounded focus:ring-0 transition duration-150 ease-in-out bg-gray-200 text-gray-700 shadow-md hover:bg-gray-300 hover:shadow-lg focus:bg-gray-300 focus:shadow-lg focus:outline-none active:bg-gray-400 active:shadow-lg" ><i class="fa fa-arrow-down"></i></button>`
            const btnDuplicate = `<button value = "${tableId}" onClick = "duplicateLineEditableTable(${paramString})" type="button" class="px-1.5 py-1 border-2 border-blue-600 inline-block font-medium text-xs leading-tight uppercase rounded focus:ring-0 transition duration-150 ease-in-out bg-blue-600 text-white shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none active:bg-blue-800 active:shadow-lg"><i class="fa fa-copy"></i></button >`
            const btnTrash = `<button value="${tableId}" onClick="trashEditableTable(${paramString})" type="button" class="px-1.5 py-1 border-2 border-red-600 inline-block font-medium text-xs leading-tight uppercase rounded focus:ring-0 transition duration-150 ease-in-out bg-red-600 text-white shadow-md hover:bg-red-700 hover:shadow-lg focus:bg-red-700 focus:shadow-lg focus:outline-none active:bg-red-800 active:shadow-lg" ><i class="fa fa-trash"></i></button>`
            renderer = `${fingerPrintInput} ${destroyInput}`
            renderer += `<div class="whitespace-nowrap flex justify-center">
                ${isOrderable ? btnUp : ''} ${
                isOrderable ? btnDown : ''
            } ${btnDuplicate} ${btnTrash}
            </div>`
        } else {
            // console.log("Rendering", column)
            let onChangeParams = '{'
            onChangeParams += 'name:"' + id + '",'
            onChangeParams += 'lineType:"' + column['lineType'] + '",'
            onChangeParams += 'table01Name:"' + column['table01Name'] + '",'
            onChangeParams += 'rowIndex: ' + newRowIndex + ','
            onChangeParams += 'saveOnChange: ' + saveOnChange + ','
            onChangeParams += 'dropdownParams ' + ','
            onChangeParams += '}'

            const onChangeDropdown4Fn = 'onChangeDropdown4(' + onChangeParams + ');'
            const makeOnChangeAdvanced = (moreCodeAfter) =>
                '' +
                '$("[id=\'' +
                id +
                "']\").on('change', function(e, dropdownParams){" +
                moreCodeAfter +
                '})'
            const readOnlyStr = column['readOnly'] ? ' readonly ' : ''
            switch (column['renderer']) {
                case 'read-only-text4':
                    if (column['dataIndex'] === 'id') {
                        if (insertedId) {
                            renderer =
                                "<input id='" +
                                id +
                                "' name='" +
                                id +
                                "' value='" +
                                insertedId +
                                "' type='hidden' />"
                            renderer +=
                                "<div class='text-center'>" +
                                "<a target='_blank' class='text-blue-500' href='" +
                                column['lineTypeRoute'] +
                                insertedId +
                                '/edit' +
                                "'>" +
                                makeId(insertedId) +
                                '</a>' +
                                '</div>'
                            renderer +=
                                '<script>' + makeOnChangeAdvanced(onChangeDropdown4Fn) + '</script>'
                        } else {
                            renderer = "<input id='" + id + "' name='" + id + "' type='hidden' />"
                        }
                    } else {
                        renderer = 'New read-only-text'
                    }
                    break
                case 'dropdown':
                    if (['status'].includes(column['dataIndex'])) {
                        renderer =
                            "<select id='" +
                            id +
                            "' name='" +
                            id +
                            "' class='" +
                            column['classList'] +
                            "'>"
                        for (let i = 0; i < column['cbbDataSource'].length; i++) {
                            const status = column['cbbDataSource'][i]
                            statusObject = column['cbbDataSourceObject'][status]
                            const selected = i === 0 ? 'selected' : ''
                            renderer +=
                                "<option value='" +
                                status +
                                "' " +
                                selected +
                                '>' +
                                statusObject?.title +
                                '</option>'
                        }
                        renderer += '</select>'
                    } else if (['entity_type'].includes(column['dataIndex'])) {
                        renderer =
                            "<select id='" +
                            id +
                            "' name='" +
                            id +
                            "' class='" +
                            column['classList'] +
                            "'>"
                        console.log(column)
                        for (let i = 0; i < column['cbbDataSource'].length; i++) {
                            const status = column['cbbDataSource'][i]
                            statusObject = column['cbbDataSourceObject'][i]
                            const selected = i === 0 ? 'selected' : ''
                            renderer +=
                                "<option value='" +
                                status +
                                "' " +
                                selected +
                                '>' +
                                statusObject +
                                '</option>'
                        }
                        renderer += '</select>'
                    } else {
                        renderer = 'Only STATUS, ENTITY_TYPE has been implemented for dropdown1.'
                    }
                    break
                case 'dropdown4':
                    multipleStr = column?.multiple ? 'multiple' : ''
                    bracket = column?.multiple ? '[]' : ''
                    if (column['readOnly'] && column['deaf']) {
                        renderer =
                            "<input id='" + id + "' name='" + id + bracket + "' type='hidden' >"
                        renderer += "<div id='" + id + "_label' class='px-2'></div>"
                    } else {
                        renderer = `<select id='${id}' name='${id} ${bracket}' ${multipleStr} ${readOnlyStr} class='${column['classList']}'></select>`
                        renderer += `<script>getEById('${id}').select2({placeholder: 'Please select', templateResult: select2FormatOption, matcher: select2Matcher,})</script>`
                    }
                    renderer += '<script>' + makeOnChangeAdvanced(onChangeDropdown4Fn) + '</script>'
                    break
                case 'toggle4':
                    renderer = `<div class="flex justify-center">
                        <label for="${id}" class="inline-flex relative items-center cursor-pointer select">
                            <input id="${id}" name="${id}" type="checkbox" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[\'\'] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>\
                        </label>
                    </div>`
                    break
                case 'number4':
                    const { numericScale = 0 } = column
                    renderer = `<input id='${id}' name='${id}' component='editable/number4' ${readOnlyStr} class='${column['classList']} ${readOnlyStr}' />`
                    if (column['dataIndex'] === 'order_no') {
                        orderNoValue =
                            getMaxValueOfAColumn(tableId, '[order_no]') + table_order_no_step
                        const reRenderFn =
                            'reRenderTableBaseOnNewOrder("' + tableId + '", dropdownParams)'
                        renderer += '<script>' + makeOnChangeAdvanced(reRenderFn) + '</script>'
                    } else {
                        // onChange = "onChangeDropdown4(" + onChangeParams + ");changeBgColor(this,\"" + tableId + "\")"
                        const changeBgColorFn = 'changeBgColor(this,"' + tableId + '");'
                        const changeFooterValue = 'changeFooterValue(this,"' + tableId + '");'
                        const parseNumber4 =
                            'parseNumber2("' +
                            id +
                            '", getEById("' +
                            id +
                            '").val(), ' +
                            numericScale +
                            ');'
                        renderer +=
                            '<script>' +
                            makeOnChangeAdvanced(
                                onChangeDropdown4Fn +
                                    changeBgColorFn +
                                    changeFooterValue +
                                    parseNumber4,
                            ) +
                            '</script>'
                    }
                    break
                case 'text4':
                    renderer = `<input id='${id}' name='${id}' ${readOnlyStr} class='${column['classList']} ${readOnlyStr}' />`
                    renderer += '<script>' + makeOnChangeAdvanced(onChangeDropdown4Fn) + '</script>'
                    break
                case 'textarea4':
                    renderer = `<textarea id='${id}' name='${id}' ${readOnlyStr} class='${column['classList']}' rows='3'></textarea>`
                    break
                case 'picker-all4':
                    const { control } = column
                    // const attributeName = control === 'picker_datetime' ? 'name1' : 'name'
                    const attributeName = 'name' // when create new, this will be submitted with the form, so name not name1
                    renderer = `<input 
                    component='editable/${control}' 
                    id='${id}' 
                    ${attributeName}='${id}' 
                    placeholder='${column['placeholder']}' 
                    class='${column['classList']} ${readOnlyStr}'
                    ${column['readOnly'] ? ' disabled ' : ''}
                    >`
                    //This line will cause save problem on SQBTS
                    // renderer += "<input type='hidden1' name='"+id+"' id='hidden_"+id+"'>"
                    const changeFooterValue = 'changeFooterValue(this,"' + tableId + '");'
                    renderer +=
                        '<script>' +
                        makeOnChangeAdvanced(onChangeDropdown4Fn + changeFooterValue) +
                        '</script>'

                    break
                case 'attachment4':
                    renderer = '<div class="to be implemented"></div>'
                    break
                case 'searchable-dialog4':
                    renderer = '<div class="">please save the document</div>'
                    break
                default:
                    renderer = 'Unknown how to render ' + column['renderer']
                    break
            }
        }
        //row.insertCell MUST run after get Max finger Print
        const cell = row.insertCell()
        const hidden = column['invisible'] ? 'hidden' : ''
        const cellId = 'cell_' + id
        cell.classList = 'dark:border-gray-600 border-r ' + hidden
        cell.id = cellId
        // console.log("Insert column", column['dataIndex'], renderer)
        const showNameStr = tableDebugJs ? id : ''
        const cellHtml = showNameStr + renderer
        getEById(cellId).html(cellHtml) //<< This is to run <script>, cell.innerHTML doesn't run

        switch (column['renderer']) {
            case 'dropdown4':
                let selected
                // console.log(isDuplicatedOrAddFromList)
                if (isDuplicatedOrAddFromList) {
                    selected = valuesOfOrigin[column['dataIndex']]
                } else {
                    switch (true) {
                        case column['value_as_parent_type']:
                            selected = $('#entityParentType').val()
                            break
                        case column['value_as_parent_id']:
                            selected = $('#entityParentId').val()
                            break
                        case column['value_as_user_id']:
                            selected = $('#currentUserId').val()
                            break
                        case column['value_as_project_id']:
                            selected = $('#entityProjectId').val()
                            break
                        case column['value_as_sub_project_id']:
                            selected = $('#entitySubProjectId').val()
                            break
                        default:
                            //This is to get the default value from set_table_column
                            selected = valuesOfOrigin[column['dataIndex']]
                            break
                        // case column['value_as_rate_exchange_month_id']:
                        //     selected = $('#entityCurrencyMonth').val()
                        //     break
                        // case column['value_as_counter_currency_id']:
                        //     selected = $('#entityCurrencyExpected').val()
                        //     break
                    }
                }
                if (column['readOnly'] && column['deaf']) {
                    getEById(id).val(selected)
                    const dataSource = ki[column['tableName']]
                    // console.log(dataSource, dataSource[selected])

                    const label = dataSource[selected]?.name
                    getEById(id + '_label').html(label)
                    toDoAfterAddedDropdown4ReadOnly.push({
                        id,
                        dataSource: k[column['tableName']],
                        tableId,
                        selected,
                    })
                    // getEById(id).trigger('change')
                } else {
                    toDoAfterAddedDropdown4.push({
                        id,
                        dataSource: k[column['tableName']],
                        tableId,
                        selected,
                    })
                }
                // console.log("DDDDDD4", column)
                break
            case 'dropdown': //<<status
                let selected1 = valuesOfOrigin[column['dataIndex']]
                if (selected1 !== undefined) {
                    // console.log("Setting status", id, 'to', selected1)
                    getEById(id).val(selected1)
                    getEById(id).trigger('change', { batchLength })
                } else {
                    //get the 1st value of the select during render
                }
                break
            default:
                const value = valuesOfOrigin[column['dataIndex']]
                const picker4config =
                    tableName == 'prod_runs'
                        ? { minDate: moment().subtract(4, 'days').format('YYYY-MM-DD') }
                        : {}
                // console.log(picker4config)
                // console.log("DDDDDD5", column, value)
                if (column['value_as_parent_type']) {
                    getEById(id).val($('#entityParentType').val())
                    getEById(id).trigger('change', { batchLength })
                    break
                }
                if (column['value_as_parent_id']) {
                    getEById(id).val($('#entityParentId').val())
                    getEById(id).trigger('change', { batchLength })
                    break
                }
                if (column['value_as_user_id']) {
                    getEById(id).val($('#userId').val())
                    getEById(id).trigger('change', { batchLength })
                    break
                }
                if (column['value_as_project_id']) {
                    getEById(id).val($('#entityProjectId').val())
                    getEById(id).trigger('change', { batchLength })
                    break
                }
                if (column['value_as_sub_project_id']) {
                    getEById(id).val($('#entitySubProjectId').val())
                    getEById(id).trigger('change', { batchLength })
                    break
                }
                if (column['dataIndex'] === 'order_no') {
                    getEById(id).val(orderNoValue)
                    getEById(id).trigger('change', { batchLength })
                    break
                }
                if (column['renderer'] === 'toggle') {
                    getEById(id)[0].checked = value
                } else if (column['control'] === 'picker_datetime') {
                    newFlatPickrDateTime(id, picker4config).setDate(value)
                    getEById('hidden_' + id).val(value)
                } else if (column['control'] === 'picker_date') {
                    newFlatPickrDate(id, picker4config).setDate(value)
                } else if (column['control'] === 'picker_time') {
                    newFlatPickrTime(id /*, picker4config*/).setDate(value)
                } else {
                    // console.log("Applying data for", id, value)
                    getEById(id).val(value)
                }

                getEById(id).trigger('change', { batchLength })
                break

            // console.log("Added new column", column['dataIndex'])
        }
    })
    // console.log(toDoAfterAddedDropdown4)
    //This is to make sure the Listen assign will assign to the existing column
    for (let i = toDoAfterAddedDropdown4.length - 1; i >= 0; i--) {
        // for (let i = 0; i < toDoAfterAddedDropdown4.length; i++) {
        //<< Have to do from length back to 0,
        //<< as the reduce of the previous dropdown may be overwritten by the later dropdown reloadDataToDropdown4
        //<< E.G: GHG template already reduce metric 1, but when metric 1 load after that, it will load whole list
        const { id, dataSource, tableId, selected } = toDoAfterAddedDropdown4[i]
        // console.log("reloadDataToDropdown4 from addANewLineFull", id)
        reloadDataToDropdown4(id, dataSource, tableId, selected)
        getEById(id).trigger('change', { batchLength })
        // console.log("Triggered change", id)
    }
    for (let i = 0; i < toDoAfterAddedDropdown4ReadOnly.length; i++) {
        const { id, dataSource, tableId, selected } = toDoAfterAddedDropdown4ReadOnly[i]
        // reloadDataToDropdown4(id, dataSource, tableId, selected)
        getEById(id).trigger('change', { batchLength })
        // console.log("Triggered change", id)
    }
    // console.log(showNoR)
    if (showNoR) {
        //<< Ignore No. column
        const noCell = row.insertCell()
        noCell.classList = 'px-1 py-1 dark:border-gray-600 border-r text-center'
        noCell.innerHTML = newRowNo
    }
    return fingerPrint
}
