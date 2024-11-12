import { getFirstFixedRightColumnIndex } from './EditableTable3FixedColumn'
import { TableParams } from './Type/EditableTable3ParamType'

export const makeTfoot = ({ dataSource, columns, tableConfig }: TableParams) => {
    const { tableDebug } = tableConfig

    const firstFixedRightIndex = getFirstFixedRightColumnIndex(columns)
    const footers = columns.map((column, index) => {
        const hiddenStr = column.invisible ? 'hidden' : ''
        const alignStr = column.align ? `text-${column.align}` : ''

        const fixed = column.fixed
            ? `table-th-fixed-${column.fixed} table-th-fixed-${column.fixed}-${index}`
            : ''

        const borderL = index == firstFixedRightIndex ? 'border-l' : ''
        const borderStr = `border-r border-t border-gray-300 ${borderL}`
        const classList = `${hiddenStr} ${alignStr} ${fixed} ${borderStr} p-1`

        if (column.footer) {
            return `<td class="${classList}">${column.footer}</td>`
        } else {
            return `<td class="${classList}"></td>`
        }
    })
    // console.log('makeTfoot', footers)
    const footerStr = footers.join('')

    const classList = `sticky z-10 bg-gray-100`
    const styleList = `bottom: 0px;`
    return `<tr class="${classList}" style="${styleList}">${footerStr}</tr>`
}
