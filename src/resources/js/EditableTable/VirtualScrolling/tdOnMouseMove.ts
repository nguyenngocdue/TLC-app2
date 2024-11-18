import { Dropdown4Edit } from '../Renderer/Dropdown/Dropdown4Edit'
import { TableParams } from '../Type/EditableTable3ParamType'

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

// const cached: { [key: string]: boolean } = {}

const callApplyPostScript = (
    listToApplyPostScript: HTMLTableCellElement[],
    params: TableParams,
) => {
    listToApplyPostScript.forEach((cell) => {
        const columnKey = cell.getAttribute('data-column-key') as string
        const tdRowIndex = cell.getAttribute('data-row') as unknown as number
        const column = params.indexedColumns[columnKey]
        // console.log('columnKey', columnKey, column)
        switch (true) {
            case column.renderer == 'dropdown':
            case column.renderer == 'dropdown4':
                const controlId = `#${params.tableName}__${columnKey}__${tdRowIndex}`
                // if (!cached[controlId]) {
                // cached[controlId] = true
                const startTime = new Date().getTime()
                const options = Dropdown4Edit.getOptionsExpensive(column)
                // $(`${controlId}`).html(options.join(''))
                $(`${controlId}`).select2({
                    data: options,
                    //Ajax will need to process filter build on the keywords
                })
                const endTime = new Date().getTime()
                console.log('Dropdown4Edit.getOptionsExpensive', controlId, endTime - startTime)
                // }
                // console.log('convert dropdown4', controlId)
                break
        }
    })
}

export const tdOnMouseMove = (e: MouseEvent, params: TableParams) => {
    if (e.target) {
        const target = e.target as HTMLElement
        const td = target.closest('td')
        if (td && td !== lastTd) {
            const { tableName, tableConfig } = params

            // Call your handler
            // console.log('mousemove td', td)
            lastTd = td
            const listToApplyPostScript: HTMLTableCellElement[] = []
            listToApplyPostScript.push(td)
            // if (tableConfig.tableDebug) td.classList.add('bg-green-400')

            // const row = td.getAttribute('data-row')
            // const col = td.getAttribute('data-col')

            // if (row && col)
            //     allAroundMatrix.forEach((matrix) => {
            //         const rowIdx = parseInt(row) + matrix[0]
            //         const colIdx = parseInt(col) + matrix[1]
            //         const cell = document.querySelector(
            //             `#${tableName} [data-row="${rowIdx}"][data-col="${colIdx}"]`,
            //         )
            //         if (cell) {
            //             // listToApplyPostScript.push(cell as HTMLTableCellElement)
            //             if (tableConfig.tableDebug) cell.classList.add('bg-green-200')
            //         }
            //     })

            // console.log('listToApplyPostScript', listToApplyPostScript)
            callApplyPostScript(listToApplyPostScript, params)
        }
    }
}

export const tdOnMouseOut = (e: MouseEvent, params: TableParams) => {
    if (e.target) {
        const target = e.target as HTMLElement
        const td = target.closest('td')
        if (td && td === lastTd) {
            const { tableName, tableConfig } = params
            // Call your handler
            // console.log('mouseout td', td)
            lastTd = null
            if (tableConfig.tableDebug) td.classList.remove('bg-green-400')

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
