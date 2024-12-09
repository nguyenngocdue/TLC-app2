import { CbbDataSourceType, TableColumnDropdown } from '../Type/EditableTable3ColumnType'

// connect to global variable k, k_by somewhere in JS, before this file is loaded
declare const k: { [key1: string]: Array<{ [key2: string]: string | number }> }
declare const k_by: { [key3: string]: CbbDataSourceType }

export const getDataSourceFromK = (dataSourceKey: string, valueField: string = 'id') => {
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
        } else {
            console.error('dataSourceKey not found', dataSourceKey)
        }
        // } else {
        // console.log('cache hit', finalKey)
    }
    return k_by[finalKey]
}

const getDataSourceFromKBy = (column: TableColumnDropdown) => {
    const { rendererAttrs = {} } = column
    const { dataSourceKey, valueField = 'id' } = rendererAttrs
    if (!dataSourceKey) {
        console.error('dataSourceKey is not defined', column)
        return {}
    }

    return getDataSourceFromK(dataSourceKey, valueField)
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

export const getNotFound = () => {
    return `<div component="CacheKByKey_getNotFound"></div>`
    // return `<div class="text-orange-300 text-center font-bold bg-orange-700 rounded">NOT FOUND ${valueField} ${cellValue}</div>`
}
