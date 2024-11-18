import { TableConfig } from '../Type/EditableTable3ConfigType'

let lastTd: HTMLTableCellElement | null = null

const allAroundMatrix = [
    [-1, -1],
    [-1, 0],
    [-1, 1],
    [0, -1],
    [0, 1],
    [1, -1],
    [1, 0],
    [1, 1],
]

export const tdOnMouseMove = (e: MouseEvent, tableName: string, tableConfig: TableConfig) => {
    if (e.target) {
        const target = e.target as HTMLElement
        const td = target.closest('td')
        if (td && td !== lastTd) {
            // Call your handler
            console.log('mousemove td', td)
            lastTd = td
            td.classList.add('bg-green-400')

            const row = td.getAttribute('data-row')
            const col = td.getAttribute('data-col')

            if (row && col)
                allAroundMatrix.forEach((matrix) => {
                    const rowIdx = parseInt(row) + matrix[0]
                    const colIdx = parseInt(col) + matrix[1]
                    const cell = document.querySelector(
                        `#${tableName} [data-row="${rowIdx}"][data-col="${colIdx}"]`,
                    )
                    if (cell) {
                        if (tableConfig.tableDebug) cell.classList.add('bg-green-200')
                    }
                })
        }
    }
}

export const tdOnMouseOut = (e: MouseEvent, tableName: string, tableConfig: TableConfig) => {
    if (e.target) {
        const target = e.target as HTMLElement
        const td = target.closest('td')
        if (td && td === lastTd) {
            // Call your handler
            console.log('mouseout td', td)
            lastTd = null
            td.classList.remove('bg-green-400')

            const row = td.getAttribute('data-row')
            const col = td.getAttribute('data-col')

            if (row && col)
                allAroundMatrix.forEach((matrix) => {
                    const rowIdx = parseInt(row) + matrix[0]
                    const colIdx = parseInt(col) + matrix[1]
                    const cell = document.querySelector(
                        `#${tableName} [data-row="${rowIdx}"][data-col="${colIdx}"]`,
                    )
                    if (cell) {
                        if (tableConfig.tableDebug) cell.classList.remove('bg-green-200')
                    }
                })
        }
    }
}
