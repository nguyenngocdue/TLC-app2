import { Str } from './EditableTable3Str'
import { TableParams } from './Type/EditableTable3Type'

export const makeThead = ({ columns }: TableParams) => {
    const renderHeader = () => {
        const result = columns.map((column) => {
            if (column.title) return `<th>${column.title}</th>`
            const niceDataIndex = Str.toHeadline(column.dataIndex)

            const defaultToolTip = `+DataIndex: ${column.dataIndex}\n+Renderer: ${column.renderer}\n+Width: ${column.width}`
            const tooltip = column.tooltip || defaultToolTip

            const hiddenStr = column.invisible ? 'hidden' : ''
            const widthStyle = column.width ? `width: ${column.width}px;` : ''

            const classList = `${hiddenStr}`

            return `<th class="${classList}" style="${widthStyle}" title="${tooltip}">
                ${niceDataIndex}
            </th>`
        })

        return result
    }

    return renderHeader().join('')
}
