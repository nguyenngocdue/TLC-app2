import { getFirstFixedRightColumnIndex, getFixedStr } from './EditableTable3FixedColumn'
import { TableParams } from './Type/EditableTable3ParamType'

export const makeTfoot = ({ dataSource, columns, tableConfig }: TableParams) => {
    const { tableDebug } = tableConfig

    const firstFixedRightIndex = getFirstFixedRightColumnIndex(columns)
    const footers = columns.map((column, index) => {
        const hiddenStr = column.invisible ? 'hidden' : ''
        const alignStr = column.align ? `text-${column.align}` : ''

        const fixedStr = getFixedStr(column.fixed, index, 'th')
        const bgStr = `bg-gray-100`
        const borderL = index == firstFixedRightIndex ? 'border-l' : ''
        const borderStr = `border-r border-t border-gray-300 ${borderL}`
        const classList = `${hiddenStr} ${alignStr} ${fixedStr} ${borderStr} ${bgStr} p-1`

        if (column.footer) {
            return `<th class="${classList}">${column.footer}</td>`
        } else {
            return `<th class="${classList}"></td>`
        }
    })
    // console.log('makeTfoot', footers)
    const footerStr = footers.join('')

    const classList = `sticky z-10 bg-gray-100`
    const styleList = `bottom: 0px;`
    return `<tr class="${classList}" style="${styleList}">${footerStr}</tr>`
}
