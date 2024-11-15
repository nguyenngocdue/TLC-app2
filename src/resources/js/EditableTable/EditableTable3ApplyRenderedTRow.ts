import { twMerge } from 'tailwind-merge'
import { makeTCell } from './EditableTable3TCell'
import { TableDataLine } from './Type/EditableTable3DataLineType'
import { TableParams } from './Type/EditableTable3ParamType'

export const applyRenderedTRow = (params: TableParams, row: TableDataLine, rowIndex: number) => {
    const { dataSource, columns, tableName } = params
    if (!dataSource.data) return ''

    const totalCells = dataSource.data.length * columns.length
    const delayInMs = Math.round(10000 / totalCells)
    // const delayInMs = 0
    // console.log('totalCells of', tableName, totalCells, delayInMs)

    columns.forEach((column) => {
        const tCell = makeTCell(params, row, column, rowIndex)

        const { rendered, tdClass, p_2, componentCase, applyPostScript } = tCell

        const p = p_2 ? 'p-2 p-2-Tbody' : ''

        const cellId = `${tableName}__${column.dataIndex}__${rowIndex}`

        const cellTd = document.getElementById(`${cellId}_td`)
        cellTd && (cellTd.className = twMerge(cellTd.className, tdClass, p))

        const cellDiv = document.getElementById(`${cellId}_div`)
        if (cellDiv) {
            cellDiv.innerHTML = rendered
            cellDiv.setAttribute('data-component-case', componentCase)
            cellDiv.classList.add('fade-in')

            setTimeout(() => {
                applyPostScript()
                cellDiv.classList.add('visible')
            }, Math.random() * delayInMs)
        }
    })
}
