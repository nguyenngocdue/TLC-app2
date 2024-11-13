import { getFirstFixedRightColumnIndex, getFixedStr } from './EditableTable3FixedColumn'
import { Str } from './EditableTable3Str'
import { TableParams } from './Type/EditableTable3ParamType'

export const makeThead = ({ columns }: TableParams) => {
    const renderHeader = () => {
        const firstFixedRightIndex = getFirstFixedRightColumnIndex(columns)
        const result = columns.map((column, index) => {
            const niceDataIndex = column.title ? column.title : Str.toHeadline(column.dataIndex)
            const subTitle = column.subTitle
                ? `<div class="text-xs text-gray-500">${column.subTitle}</div>`
                : ''

            const defaultToolTip = `+DataIndex: ${column.dataIndex}\n+Renderer: ${column.renderer}\n+Width: ${column.width}`
            const tooltip = column.tooltip || defaultToolTip

            const hiddenStr = column.invisible ? 'hidden' : ''
            const widthStyle = column.width ? `width: ${column.width}px;` : ''

            const fixedStr = getFixedStr(column.fixed, index, 'th')
            const bgStr = `bg-gray-100`
            const borderL = index == firstFixedRightIndex ? 'border-l' : ''
            const borderStr = `border-b border-r border-gray-300 ${borderL}`
            const classList = `${hiddenStr} ${fixedStr} ${borderStr} ${bgStr}`

            return `<th class="${classList}" style="${widthStyle}" title="${tooltip}">
                ${niceDataIndex}
                ${subTitle}
            </th>`
        })

        return result
    }

    return renderHeader().join('')
}
