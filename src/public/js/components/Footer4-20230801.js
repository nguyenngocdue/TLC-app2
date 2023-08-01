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

const median = (arr) => {
    const sortedArr = arr.sort((a, b) => a - b);
    const mid = Math.floor(sortedArr.length / 2);

    if (sortedArr.length % 2 === 0) {
        return (sortedArr[mid - 1] + sortedArr[mid]) / 2;
    } else {
        return sortedArr[mid];
    }
};

function calculateFooterValue(operator, tableId, fieldName) {
    const aggList = ['agg_none', 'agg_count_all', 'agg_sum', 'agg_avg', 'agg_median', 'agg_min', 'agg_max', 'agg_range',];
    const count = getAllRows(tableId).length
    // console.log(operator, tableId, fieldName, count)
    const array = []
    for (let i = 0; i < count; i++) {
        const name = tableId + "[" + fieldName + "][" + i + "]"
        array.push(1 * numberRemoveComma(getEById(name).val()))
    }
    const result = {}
    result['agg_none'] = '';
    result['agg_count_all'] = array.length;
    result['agg_sum'] = array.reduce((accumulator, a) => accumulator + a, 0)
    result['agg_avg'] = array.length ? (result['agg_sum'] / result['agg_count_all']) : 0
    result['agg_min'] = array.reduce((acc, curr) => Math.min(acc, curr))
    result['agg_max'] = array.reduce((acc, curr) => Math.max(acc, curr))
    result['agg_range'] = (result['agg_max'] - result['agg_min'])
    result['agg_median'] = median(array)
    // console.log(result)

    for (let i = 0; i < aggList.length; i++) {
        const agg = aggList[i]
        const footerName = tableId + "[footer][" + fieldName + "][" + agg + "]"
        // console.log(footerName, result[agg])
        getEById(footerName).val(agg == 'agg_none' ? '' : (result[agg] * 1).toFixed(2))
        getEById(footerName).trigger('change')
    }
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