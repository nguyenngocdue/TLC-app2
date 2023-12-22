import { EditableModeWrapper } from './TableMode/EditableModeWrapper'
import { PrintableMode } from './TableMode/PrintableMode'

import { postRender, preRender } from './PostRender'

const tableRenderer = (params) => {
    const { modeName } = params.settings
    switch (modeName) {
        case "editable-mode": return EditableModeWrapper(params)
        case "printable-mode": return PrintableMode(params)
        default: return `Unknown how to render table mode [${modeName}]`
    }
}

export const keyBy = (array, keyName) => {
    const result = {}
    for (let i = 0; i < array.length; i++) result[array[i][keyName]] = array[i]
    return result
}

const keyByForDataSource = (array, keyName) => {
    array.forEach(column => {
        if (column.dataSource && Array.isArray(column.dataSource)) {
            const dsIndexed = keyBy(column.dataSource, keyName)
            column.dataSource = dsIndexed
        }
    })
    return array
}

export const EditableTable = (params) => {
    // console.log(params)

    const columns0 = keyByForDataSource(params.columns, 'id')
    const columns = keyBy(columns0, 'dataIndex')

    params = { ...params, columns }
    preRender(params)
    const table = tableRenderer(params)
    postRender(params)
    return table
}
