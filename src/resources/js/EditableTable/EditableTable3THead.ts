import { getTooltip } from './EditableTable3DefaultValue'
import { getFirstFixedRightColumnIndex, getFixedStr } from './EditableTable3FixedColumn'
import { Str } from './EditableTable3Str'
import { TableParams } from './Type/EditableTable3ParamType'

export const makeThead = ({ columns, tableConfig }: TableParams) => {
    const firstFixedRightIndex = getFirstFixedRightColumnIndex(columns)
    const result = columns.map((column, index) => {
        const niceDataIndex = column.title ? column.title : Str.toHeadline(column.dataIndex)
        const tooltipStr = getTooltip(column)
        const hiddenStr = column.invisible ? 'hidden' : ''
        const widthStyle = column.width ? `width: ${column.width}px;` : ''

        const fixedStr = getFixedStr(column.fixed, index, 'th')
        const bgStr = `bg-gray-100`
        const textStr = `text-xs text-xs-vw text-gray-700`
        const borderL = index == firstFixedRightIndex ? 'border-l' : ''
        const borderStr = `border-b border-r border-gray-300 ${borderL}`
        const rotateThStr = tableConfig.rotate45Width ? 'rotated-title-left-th' : ''
        const rotateDivStr = tableConfig.rotate45Width
            ? `rotated-title-div-${tableConfig.rotate45Width} text-left`
            : ``

        const rotate45Height =
            tableConfig.rotate45Height || (tableConfig.rotate45Width || 100) / 1.41421 // Math.sqrt(2) side of square
        const rotateThStyle = `height: ${rotate45Height}px;`
        const rotateDivStyle = `width: ${tableConfig.rotate45Width}px;`

        const classList = `${hiddenStr} ${fixedStr} ${borderStr} ${bgStr} ${textStr} ${rotateThStr}`
        const styleThStr = `${widthStyle} ${rotateThStyle}`
        const styleDivStr = `${rotateDivStyle}`

        return `<th class="${classList}" style="${styleThStr}" title="${tooltipStr}">
            <div class="${rotateDivStr}" style="${styleDivStr}">
                ${niceDataIndex}
                <div class="text-xs text-gray-500">${column.subTitle || ''}</div>
            </div>
        </th>`
    })

    return result.join('')
}
