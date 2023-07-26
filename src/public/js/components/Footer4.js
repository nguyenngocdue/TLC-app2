function onChangeDropdown4AggregateFromTable(id, value) {
    // console.log("onChangeDropdown4AggregateFromTable", id)
    for (let i = 0; i < listenersOfDropdown2.length; i++) {
        const listener = listenersOfDropdown2[i]
        const triggers = listener['triggers']
        // console.log(triggers, id)
        if (triggers.includes(id)) {
            const targetName = listener['column_name']
            getEById(targetName).val(value)
            getEById(targetName).trigger('change')
            // console.log(listener)
        }
    }
}

function calculateFooterValue(operator, tableId, fieldName) {
    const count = getAllRows(tableId).length
    // console.log(operator, tableId, fieldName, count)
    let result = 0;
    for (let i = 0; i < count; i++) {
        const name = tableId + "[" + fieldName + "][" + i + "]"
        switch (operator) {
            case 'agg_sum':
                result += 1 * numberRemoveComma(getEById(name).val())
                break
            default:
                console.log("Unknown operator '" + operator + "'")
                break
        }
    }
    const footerName = tableId + "[footer][" + fieldName + "]"
    getEById(footerName).val(result.toFixed(2))
    getEById(footerName).trigger('change')
}

function changeFooterValue(object, tableId) {
    const fieldName = getFieldNameInTable01FormatJS(object.name, tableId);
    // console.log('footer', tableId, object, fieldName);
    const table = tableObject[tableId];
    const { columns } = table
    for (let i = 0; i < columns.length; i++) {
        const column = columns[i]
        const { footer, dataIndex } = column
        // console.log(footer)
        if (dataIndex == fieldName && footer !== undefined) {
            calculateFooterValue(footer, tableId, fieldName)
        }
    }
    // console.log(fieldName, table);
}