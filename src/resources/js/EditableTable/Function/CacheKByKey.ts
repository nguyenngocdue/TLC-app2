import { CbbDataSourceType, TableColumnDropdown } from '../Type/EditableTable3ColumnType'

// connect to global variable k, k_by somewhere in JS, before this file is loaded
declare const k: { [key1: string]: Array<{ [key2: string]: string | number }> }
declare const k_by: { [key3: string]: CbbDataSourceType }

const getDataSourceFromKBy = (column: TableColumnDropdown) => {
    const { rendererAttrs = {} } = column
    const { dataSourceKey, valueField = 'id' } = rendererAttrs

    if (!dataSourceKey) return {}
    const finalKey = `${dataSourceKey}_by_${valueField}`
    if (k_by[finalKey] === undefined) {
        k_by[finalKey] = {}
        if (k[dataSourceKey]) {
            for (let i = 0; i < k[dataSourceKey].length; i++) {
                const item = k[dataSourceKey][i]
                const valueOfKey = item[valueField]
                // console.log(dataSourceKey, valueOfKey, item)
                k_by[finalKey][valueOfKey] = item
            }
        }
        // } else {
        // console.log('cache hit', finalKey)
    }
    return k_by[finalKey]
}

export const getDataSource = (column: TableColumnDropdown) => {
    let cbbDataSource: CbbDataSourceType = {}
    const { rendererAttrs = {} } = column
    const { dataSourceKey, dataSource = {} } = rendererAttrs
    if (typeof dataSourceKey == 'string') {
        cbbDataSource = getDataSourceFromKBy(column)
    } else {
        cbbDataSource = dataSource
    }
    return cbbDataSource
}

export const getNotFound = (valueField: string, cellValue: string) => {
    return `<div class="text-orange-300 text-center font-bold bg-orange-700 rounded">NOT FOUND ${valueField} ${cellValue}</div>`
}
