import { ValueObject4 } from './Controls/ValueObject4'
import { getFirstFixedRightColumnIndex, getFixedStr } from './EditableTable3FixedColumn'
import { TableParams } from './Type/EditableTable3ParamType'

export const makeThead2nd = (params: TableParams) => {
    const { columns, dataHeader } = params
    if (!dataHeader) return ''
    // console.log('makeThead2nd', columns, dataHeader)
    const renderHeader = () => {
        const firstFixedRightIndex = getFirstFixedRightColumnIndex(columns)
        const result = columns.map((column, index) => {
            const defaultToolTip = `+DataIndex: ${column.dataIndex}\n+Renderer: ${column.renderer}\n+Width: ${column.width}`
            const tooltip = column.tooltip || defaultToolTip

            const hiddenStr = column.invisible ? 'hidden' : ''
            const widthStyle = column.width ? `width: ${column.width}px;` : ''

            let sndHeader = ''
            let classStr = ''
            if (dataHeader[column.dataIndex]) {
                const result = new ValueObject4(
                    dataHeader[column.dataIndex],
                    params,
                    {},
                    column,
                    0,
                ).render()
                sndHeader = result.rendered
                classStr = result.classStr || ''
            }

            const fixedStr = getFixedStr(column.fixed, index, 'th')
            const bgStr = `bg-gray-100`
            const borderL = index == firstFixedRightIndex ? 'border-l' : ''
            const borderStr = `border-b border-r border-gray-300 ${borderL}`
            const classList = `${hiddenStr} ${fixedStr} ${borderStr} ${bgStr} ${classStr}`

            return `<th class="${classList}" style="${widthStyle}" title="${tooltip}">
                ${sndHeader}                
            </th>`
        })

        return result
    }

    return renderHeader().join('')
}
