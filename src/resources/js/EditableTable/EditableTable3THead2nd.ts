import { ValueObject4 } from './Renderer/ValueObject/ValueObject4'
import { getFirstFixedRightColumnIndex, getFixedStr } from './EditableTable3FixedColumn'
import { TableParams } from './Type/EditableTable3ParamType'

export const makeThead2nd = (params: TableParams) => {
    const { columns, dataHeader } = params
    if (!dataHeader) return ''
    // console.log('makeThead2nd', columns, dataHeader)
    const renderHeader = () => {
        let hasActualText = false
        const firstFixedRightIndex = getFirstFixedRightColumnIndex(columns)
        const result = columns.map((column, index) => {
            const tooltip = column.tooltip || ''

            const hiddenStr = column.invisible ? 'hidden' : ''
            const widthStyle = column.width ? `width: ${column.width}px;` : ''

            let sndHeader = ''
            let classStr = ''
            if (dataHeader[column.dataIndex]) {
                const cellValue = dataHeader[column.dataIndex]
                const rendererParams = {
                    cellValue,
                    params,
                    dataLine: dataHeader,
                    column,
                    rowIndex: 0,
                }
                const result = new ValueObject4(rendererParams).render()
                sndHeader = result.rendered
                classStr = result.classStr || ''
            }

            const fixedStr = getFixedStr(column.fixed, index, 'th')
            const bgStr = `bg-gray-100`
            const textStr = `text-xs text-xs-vw text-gray-500`
            const borderL = index == firstFixedRightIndex ? 'border-l' : ''
            const borderStr = `border-b border-r border-gray-300 ${borderL}`
            const classList = `${hiddenStr} ${fixedStr} ${borderStr} ${bgStr} ${textStr} ${classStr} text-center`
            hasActualText ||= !!sndHeader

            return `<th class="${classList}" style="${widthStyle}" title="${tooltip}">
                ${sndHeader}                
            </th>`
        })

        if (hasActualText) return result.join('')
        return ''
    }

    return renderHeader()
}
