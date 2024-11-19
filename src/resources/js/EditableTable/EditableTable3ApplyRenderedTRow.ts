import { twMerge } from 'tailwind-merge'
import { makeTCell } from './EditableTable3TCell'
import { TableDataLine } from './Type/EditableTable3DataLineType'
import { TableParams } from './Type/EditableTable3ParamType'

export const applyRenderedTRow = (params: TableParams, row: TableDataLine, rowIndex: number) => {
    const { dataSource, columns, tableName, tableConfig } = params
    if (!dataSource.data) return ''

    // console.log('applying rendered row', row, rowIndex)

    columns.forEach((column) => {
        const tCell = makeTCell(params, row, column, rowIndex)
        // console.log('making column for row', column, rowIndex, tCell)
        const {
            rendered,
            tdClass,
            divClass,
            tdStyle,
            divStyle,
            p_2,
            componentCase,
            applyPostScript,
        } = tCell
        const tdStyleString = Object.entries(tdStyle)
            .map(([key, value]) => `${key}: ${value};`)
            .join(' ')

        const divStyleString = Object.entries(divStyle)
            .map(([key, value]) => `${key}: ${value};`)
            .join(' ')

        // console.log('divStyleString', divStyleString)

        const p = p_2 ? 'p-2 p-2-Tbody' : ''
        const truncate = `overflow-ellipsis overflow-hidden`
        const cellId = `${tableName}__${column.dataIndex}__${column.renderer}__${rowIndex}`
        const cellTd = document.getElementById(`${cellId}__td`)
        if (cellTd) {
            cellTd.className = twMerge(cellTd.className, tdClass, p)
            cellTd.style.cssText = tdStyleString
        }

        const cellDiv = document.getElementById(`${cellId}__div`)
        if (cellDiv) {
            cellDiv.className = twMerge(cellDiv.className, divClass, truncate)
            cellDiv.style.cssText = `${divStyleString}`
        }

        if (cellDiv) {
            const animationDelay = tableConfig.animationDelay || 0
            cellDiv.innerHTML = rendered
            cellDiv.setAttribute('data-component-case', componentCase)
            if (animationDelay) {
                cellDiv.classList.add('fade-in')
            } else {
                cellDiv.classList.add('visible')
            }

            setTimeout(() => {
                // if (column.dataIndex === 'user_id')
                applyPostScript()

                if (animationDelay) cellDiv.classList.add('visible')
            }, Math.random() * (tableConfig.animationDelay || 0))
        }
    })
}
