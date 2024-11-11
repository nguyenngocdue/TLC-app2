import { Str } from './EditableTable3Str'
import { TableParams } from './Type/EditableTable3Type'

export const makeThead = ({ columns }: TableParams) => {
    const renderHeader = () => {
        const result = columns.map((column) => {
            if (column.title) return `<th>${column.title}</th>`
            const niceDataIndex = Str.toHeadline(column.dataIndex)

            const hiddenStr = column.invisible ? 'hidden' : ''
            const classList = `${hiddenStr}`

            const defaultToolTip = `DataIndex: ${column.dataIndex}\nRenderer: ${column.renderer}\nWidth: ${column.width}`
            const tooltip = column.tooltip || defaultToolTip

            return `<th class="${classList}" title="${tooltip}">
                ${niceDataIndex}
            </th>`
        })

        return result
    }

    return renderHeader().join('')
}
