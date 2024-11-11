import { TableParams } from './Type/EditableTable3Type'

export const makeColGroup = ({ columns }: TableParams) => {
    return columns
        .map((column) => {
            if (column.invisible) return null
            if (column.width) return `<col name="${column.dataIndex}" style="width:${column.width}px;" />`
            return `<col name="${column.dataIndex}" />`
        })
        .filter((col) => col !== null)
        .join('')
}
