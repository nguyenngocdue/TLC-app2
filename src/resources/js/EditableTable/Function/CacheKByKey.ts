import { TableColumnDropdown } from '../Type/EditableTable3ColumnType'

// connect to global variable k, k_by somewhere in JS, before this file is loaded
declare const k: { [key1: string]: Array<{ [key2: string]: string | number }> }
declare const k_by: { [key3: string]: { [key4: string]: { [key5: string]: string | number } } }

export const getDataSourceFromKBy = (column: TableColumnDropdown) => {
    const { rendererAttrs = {} } = column
    const { dataSourceKey = 'undefined321', dataSource = [], valueField = 'id' } = rendererAttrs

    const finalKey = `${dataSourceKey}_by_${valueField}`
    if (k_by[finalKey] === undefined) {
        k_by[finalKey] = {}
        for (let i = 0; i < k[dataSourceKey].length; i++) {
            const item = k[dataSourceKey][i]
            const valueOfKey = item[valueField]
            // console.log(dataSourceKey, valueOfKey, item)
            k_by[finalKey][valueOfKey] = item
        }
    }
    const cbbDataSource = k_by[finalKey]
    // console.log('cbbDataSource', cbbDataSource)
    return cbbDataSource
}
