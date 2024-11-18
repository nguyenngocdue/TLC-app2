import { twMerge } from 'tailwind-merge'
import { getFixedStr } from './EditableTable3FixedColumn'
import { TableColumn } from './Type/EditableTable3ColumnType'

export const makeTbodyTdEmpty = (
    tableName: string,
    column: TableColumn,
    rowIndex: number,
    columnIndex: number,
    firstFixedRightIndex: number,
) => {
    const hiddenStr = column.invisible ? 'hidden' : ''
    const alignStr = column.align ? `text-${column.align}` : ''
    const dataIndex = column.dataIndex
    const fixedStr = getFixedStr(column.fixed, columnIndex, 'td')
    const textStr = `text-sm text-sm-vw`
    const borderL = columnIndex == firstFixedRightIndex ? 'border-l' : ''
    const borderStr = `border-b border-r border-gray-300 ${borderL}`
    const classList = twMerge(`${hiddenStr} ${alignStr} ${fixedStr} ${borderStr} ${textStr}`)

    const widthStr = column.width ? `width: ${column.width}px;` : ''
    const styleList = `${widthStr}`
    const cellId = `${tableName}__${dataIndex}__${rowIndex}`
    return `<td 
                    id="${cellId}__td"
                    class="${classList}" 
                    style="${styleList}" 
                    data-row="${rowIndex}"
                    data-col="${columnIndex}"                    
                    ><div id="${cellId}_div" class="min-h-5 fade-in"></div></td>`
}
