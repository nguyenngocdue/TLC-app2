import { twMerge } from 'tailwind-merge'
import { getFirstFixedRightColumnIndex, getFixedStr } from './FixedColumn/EditableTable3FixedColumn'
import { TableParams } from './Type/EditableTable3ParamType'

export const makeTfoot = ({ dataSource, columns, tableConfig }: TableParams) => {
    const firstFixedRightIndex = getFirstFixedRightColumnIndex(columns)
    let hasActualText = false
    const footers = columns.map((column, index) => {
        const hiddenStr = column.invisible ? 'hidden' : ''
        const alignStr = column.align ? `text-${column.align}` : ''

        const fixedStr = getFixedStr(column.fixed, index, 'th')
        const textStr = `text-xs text-xs-vw`
        const bgStr = `bg-gray-100`
        const borderL = index == firstFixedRightIndex ? 'border-l' : ''
        const borderStr = `border-r border-t border-b border-gray-300 ${borderL}`
        const classList = twMerge(
            `${hiddenStr} ${alignStr} ${fixedStr} ${borderStr} ${bgStr} ${textStr} p-1`,
        )

        if (column.footer) {
            hasActualText ||= !!column.footer
            return `<th class="${classList}">${column.footer}</td>`
        } else {
            return `<th class="${classList}"></td>`
        }
    })
    if (!hasActualText) return ''
    // console.log('makeTfoot', footers)
    const footerStr = footers.join('')

    const classList = `sticky z-10 bg-gray-100`
    const styleList = `bottom: -1px;`
    return `<tr class="${classList}" style="${styleList}">${footerStr}</tr>`
}
