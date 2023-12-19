function onChangeDropdown4AggregateFromTable(id, value) {
    // console.log("onChangeDropdown4AggregateFromTable", id, value)
    const props = superProps['props']
    for (let i = 0; i < listenersOfDropdown2.length; i++) {
        const listener = listenersOfDropdown2[i]
        const triggers = listener['triggers']
        // console.log(triggers, id)
        if (triggers.includes(id)) {
            const targetName = listener['column_name']
            const prop = props["_" + targetName]
            const { control } = prop
            // console.log(targetName, prop.control)
            switch (control) {
                case 'picker_date':
                    const picker0 = newFlatPickrDate(targetName)
                    if (value === '1970-01-01') picker0.clear();
                    else picker0.setDate(value);
                    break;
                case 'picker_time':
                    newFlatPickrTime(targetName).setDate(value);
                    break;
                case 'picker_datetime':
                    const picker1 = newFlatPickrDateTime(targetName)
                    if (value === '1970-01-01 00:00:00') picker1.clear();
                    else picker1.setDate(value);
                    break;
                default:
                    getEById(targetName).val(value)
                    break;
            }
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

const countUniqueValues = (arr) => {
    const valueCounts = {};
    arr.forEach(value => {
        valueCounts[value] = (valueCounts[value] || 0) + 1;
    });

    return Object.keys(valueCounts).length;
}

const footerAggList = [
    'agg_none',
    'agg_count_all',
    'agg_sum',
    'agg_avg',
    'agg_median',
    'agg_min',
    'agg_max',
    'agg_range',
    'agg_count_unique_values',
    'agg_unique_values',
];

function calculateFooterValue(table01Name, eloquentFn, fieldName, control) {
    const count = getAllRows(table01Name).length
    // console.log(table01Name, fieldName, count)
    const array = []
    for (let i = 0; i < count; i++) {
        const name = table01Name + "[" + fieldName + "][" + i + "]"
        const value = getEById(name).val()
        switch (control) {
            case 'picker_date':
                if (value) array.push(moment(value, "YYYY-MM-DD") / 1000) //<<convert milliseconds to seconds
                break
            case 'picker_datetime':
                if (value) array.push(moment(value, "YYYY-MM-DD HH:mm") / 1000) //<<convert milliseconds to seconds
                break
            case 'picker_time':
                if (value) array.push(moment(value, "HH:mm") / 1000) //<<convert milliseconds to seconds
                break
            default:
                array.push(1 * numberRemoveComma(value))
                break
        }
    }
    // console.log(array, control)
    const result = {}
    result['agg_none'] = '';
    result['agg_count_all'] = array.length;
    result['agg_count_unique_values'] = countUniqueValues(array);
    result['agg_sum'] = array.length ? array.reduce((accumulator, a) => accumulator + a, 0) : 0
    result['agg_avg'] = array.length ? (result['agg_sum'] / result['agg_count_all']) : 0
    result['agg_min'] = array.length ? array.reduce((acc, curr) => Math.min(acc, curr)) : 0
    result['agg_max'] = array.length ? array.reduce((acc, curr) => Math.max(acc, curr)) : 0
    result['agg_range'] = (result['agg_max'] - result['agg_min'])
    result['agg_median'] = median(array)
    // console.log(result)

    switch (control) {
        case 'picker_date':
            const format0 = "YYYY-MM-DD";
            result['agg_avg'] = moment.unix(result['agg_avg']).format(format0);
            result['agg_min'] = moment.unix(result['agg_min']).format(format0);
            result['agg_max'] = moment.unix(result['agg_max']).format(format0);
            result['agg_median'] = moment.unix(result['agg_median']).format(format0);
            result['agg_sum'] = "maybe_meaningless";
            break
        case 'picker_datetime':
            const format1 = "YYYY-MM-DD HH:mm";
            result['agg_avg'] = moment.unix(result['agg_avg']).format(format1);
            result['agg_min'] = moment.unix(result['agg_min']).format(format1);
            result['agg_max'] = moment.unix(result['agg_max']).format(format1);
            result['agg_median'] = moment.unix(result['agg_median']).format(format1);
            result['agg_sum'] = "maybe_meaningless";
            break
        case 'picker_time':
            const format2 = "HH:mm";
            result['agg_avg'] = moment.unix(result['agg_avg']).format(format2);
            result['agg_min'] = moment.unix(result['agg_min']).format(format2);
            result['agg_max'] = moment.unix(result['agg_max']).format(format2);
            result['agg_median'] = moment.unix(result['agg_median']).format(format2);
            result['agg_sum'] = "maybe_meaningless";
            break
        default:
            result['agg_avg'] = result['agg_avg'].toFixed(2)
            result['agg_min'] = result['agg_min'].toFixed(2)
            result['agg_max'] = result['agg_max'].toFixed(2)
            result['agg_median'] = result['agg_median'].toFixed(2)
            result['agg_sum'] = result['agg_sum'].toFixed(2)
            break
    }

    for (let i = 0; i < footerAggList.length; i++) {
        const agg = footerAggList[i]
        const footerName = eloquentFn + "[footer][" + fieldName + "][" + agg + "]"
        // console.log(footerName, result[agg])
        getEById(footerName).val(agg == 'agg_none' ? '' : result[agg])
        // getEById(footerName).val(agg == 'agg_none' ? '' : (result[agg] * 1).toFixed(2))
        getEById(footerName).trigger('change')
    }
}

function changeFooterValue(object, table01Name) {
    const fieldName = getFieldNameInTable01FormatJS(object.name, table01Name);
    // console.log('footer', table01Name, object, fieldName);
    const table = tableObject[table01Name];
    const { columns, eloquentFn } = table
    for (let i = 0; i < columns.length; i++) {
        const column = columns[i]
        // console.log(column)
        const { footer, dataIndex, properties = {}, control: control_in_col = '' } = column
        // console.log(footer)
        const { control: control_in_properties = '' } = properties
        const control = control_in_properties || control_in_col
        // console.log(properties, control)
        if (dataIndex == fieldName && footer !== undefined) {
            calculateFooterValue(table01Name, eloquentFn, fieldName, control)
        }
    }
    // console.log(fieldName, table);
}