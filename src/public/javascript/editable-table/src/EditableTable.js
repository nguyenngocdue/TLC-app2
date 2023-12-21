import { EditableModeWrapper } from './TableMode/EditableModeWrapper'
import { PrintableMode } from './TableMode/PrintableMode'

import { postRender } from './PostRender'

const tableRenderer = (params) => {
    const { modeName } = params.settings
    switch (modeName) {
        case "editable-mode": return EditableModeWrapper(params)
        case "printable-mode": return PrintableMode(params)
        default: return `Unknown how to render table mode [${modeName}]`
    }
}

const keyBy = (array, keyName) => {
    const result = {}
    for (let i = 0; i < array.length; i++) result[array[i][keyName]] = array[i]
    return result
}

const keyByForDataSource = (array, keyName) => {
    array.forEach(column => {
        if (column.dataSource) column.dataSourceIndexed = keyBy(column.dataSource, keyName)
    })
    return array
}

export const EditableTable = (params) => {
    // console.log(params)

    const columns = keyByForDataSource(params.columns, 'id')
    const columnsIndexed = keyBy(columns, 'dataIndex')

    params = { ...params, columnsIndexed }
    const table = tableRenderer(params)
    postRender(params);
    return table
}
