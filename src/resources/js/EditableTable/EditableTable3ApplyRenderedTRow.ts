import { twMerge } from 'tailwind-merge'
import { makeTCell } from './EditableTable3TCell'
import { TableDataLine } from './Type/EditableTable3DataLineType'
import { TableParams } from './Type/EditableTable3ParamType'

export const applyRenderedTRow = (params: TableParams, row: TableDataLine, rowIndex: number) => {
    const { dataSource, columns, tableName, tableConfig } = params
    if (!dataSource.data) return ''

    columns.forEach((column) => {
        const tCell = makeTCell(params, row, column, rowIndex)
        const { rendered, tdClass, p_2, componentCase, applyPostScript } = tCell
        const p = p_2 ? 'p-2 p-2-Tbody' : ''
        const cellId = `${tableName}__${column.dataIndex}__${rowIndex}`
        const cellTd = document.getElementById(`${cellId}__td`)
        cellTd && (cellTd.className = twMerge(cellTd.className, tdClass, p))

        const cellDiv = document.getElementById(`${cellId}_div`)
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
