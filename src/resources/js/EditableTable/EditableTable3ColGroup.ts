import { TableParams } from './Type/EditableTable3ParamType'

export const makeColGroup = ({ columns }: TableParams) => {
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

export const calTableTrueWidth = ({ columns }: TableParams) => {
    return columns
        .map((column) => {
            if (column.invisible) return 0
            if (column.width) return column.width
            return 0
        })
        .reduce((acc, cur) => acc + cur, 0)
}
