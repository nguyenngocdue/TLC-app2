import { twMerge } from 'tailwind-merge'
import { getTooltip } from './EditableTable3DefaultValue'
import { getFirstFixedRightColumnIndex, getFixedStr } from './FixedColumn/EditableTable3FixedColumn'
import { Str } from './Functions'
import { TableParams } from './Type/EditableTable3ParamType'
import { TableColumn } from './Type/EditableTable3ColumnType'
import { renderMasterCB } from './Renderer/IdAction/MasterCheckbox'

declare let tableColumns: { [tableName: string]: TableColumn[] }

export const makeThead = ({ tableName, tableConfig }: TableParams) => {
    const columns = tableColumns[tableName]
    const firstFixedRightIndex = getFirstFixedRightColumnIndex(columns)
    let colspanSkipCounter = 0
    const result = columns.map((column, index) => {
        if (colspanSkipCounter > 0) {
            colspanSkipCounter--
            return ''
        }

        if (column.colspan) colspanSkipCounter = column.colspan - 1

        const title = column.title !== undefined ? column.title : Str.toHeadline(column.dataIndex)
        let masterCb = ``
        if (['checkbox', 'checkbox_for_line'].includes(column.renderer)) {
            masterCb = renderMasterCB(tableName, column)
        }
        const tooltipStr = getTooltip(column)
        const hiddenStr = column.invisible ? 'hidden' : ''
        const widthStyle = column.width ? `width: ${column.width}px;` : ''

        const fixedStr = getFixedStr(column.fixed, index, 'th')
        const bgStr = `bg-gray-100`
        const textStr = `text-xs text-xs-vw text-gray-700`
        const borderL = index == firstFixedRightIndex ? 'border-l' : ''
        const borderStr = `border-b border-r border-t border-gray-300 ${borderL}`
        const rotateThStr = tableConfig.rotate45Width ? 'rotated-title-left-th' : ''
        const rotateDivStr = tableConfig.rotate45Width
            ? `rotated-title-div-${tableConfig.rotate45Width} text-left`
            : ``

        const rotate45Height =
            tableConfig.rotate45Height || (tableConfig.rotate45Width || 100) / 1.41421 // Math.sqrt(2) side of square
        const rotateThStyle = `height: ${rotate45Height}px;`
        const rotateDivStyle = tableConfig.rotate45Width
            ? `width: ${tableConfig.rotate45Width}px;`
            : ``

        const classList = twMerge(
            `first-header-${index} ${hiddenStr} ${fixedStr} ${borderStr} ${bgStr} ${textStr} ${rotateThStr} text-center px-1`,
        )
        const styleThStr = `${widthStyle} ${rotateThStyle}`
        const styleDivStr = `${rotateDivStyle}`

        return `<th 
            class="${classList}" 
            style="${styleThStr}" 
            title="${tooltipStr}" 
            colspan="${column.colspan || ''}"
        >
            <div class="${rotateDivStr}" style="${styleDivStr}">
                <div class="">${title}</div>
                <div class="text-xs text-gray-500">${column.subTitle || ''}</div>
                ${masterCb}
            </div>
        </th>`
    })

    return result.join('')
}
