import { Str } from './EditableTable3Str'
import { TableParams } from './Type/EditableTable3ParamType'

export const makeThead2nd = ({ columns, dataHeader }: TableParams) => {
    if (!dataHeader) return ''
    // console.log('makeThead2nd', columns, dataHeader)
    const renderHeader = () => {
        const result = columns.map((column, index) => {
            const defaultToolTip = `+DataIndex: ${column.dataIndex}\n+Renderer: ${column.renderer}\n+Width: ${column.width}`
            const tooltip = column.tooltip || defaultToolTip

            const hiddenStr = column.invisible ? 'hidden' : ''
            const widthStyle = column.width ? `width: ${column.width}px;` : ''

            const fixed = column.fixed
                ? `table-th-fixed-${column.fixed} table-th-fixed-${column.fixed}-${index}`
                : ''
            const classList = `${hiddenStr} ${fixed} border-b border-r border-gray-300`
            let sndHeader = ''
            if (dataHeader[column.dataIndex])
                sndHeader = dataHeader[column.dataIndex] as unknown as string

            return `<th class="${classList}" style="${widthStyle}" title="${tooltip}">
                ${sndHeader}                
            </th>`
        })

        return result
    }

    return renderHeader().join('')
}
