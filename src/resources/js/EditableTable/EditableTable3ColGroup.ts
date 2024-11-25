import { TableColumn } from './Type/EditableTable3ColumnType'
import { TableParams } from './Type/EditableTable3ParamType'

declare let tableColumns: { [tableName: string]: TableColumn[] }

export const makeColGroup = ({ tableName }: TableParams) => {
    const columns = tableColumns[tableName]
    return columns
        .map((column) => {
            if (column.invisible) return null
            if (column.width)
                return `<col name="${column.dataIndex}" style="width:${column.width}px;" />`
            return `<col name="${column.dataIndex}" />`
        })
        .filter((col) => col !== null)
        .join('')
}

export const calTableTrueWidth = ({ tableName }: TableParams) => {
    const columns = tableColumns[tableName]
    return columns
        .map((column) => {
            if (column.invisible) return 0
            if (column.width) return column.width * 1
            return 0
        })
        .reduce((acc, cur) => acc + cur, 0)
}
