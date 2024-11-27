import { twMerge } from 'tailwind-merge'
import { makeTCell } from './EditableTable3TCell'
import { LengthAware, TableDataLine } from './Type/EditableTable3DataLineType'
import { TableParams } from './Type/EditableTable3ParamType'
import { TableColumn } from './Type/EditableTable3ColumnType'

declare let tableData: { [tableName: string]: LengthAware }
declare let tableColumns: { [tableName: string]: TableColumn[] }

export const applyRenderedTRow = (params: TableParams, row: TableDataLine, rowIndex: number) => {
    const { tableName, tableConfig } = params

    const dataSource = tableData[tableName]
    if (!dataSource.data) return ''
    const columns = tableColumns[tableName]
    // console.log('applying rendered row', row, rowIndex)

    columns.forEach((column) => {
        const tCell = makeTCell(params, row, column, rowIndex)
        // console.log('making column for row', column, rowIndex, tCell)
        const {
            rendered,
            tdClass,
            tdStyle,
            tdTooltip,
            divClass,
            divStyle,
            divTooltip,
            p_2,
            componentCase,

            applyPostRenderScript,
            applyOnMouseMoveScript,
            applyOnChangeScript,
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
        const { dataIndex, renderer } = column
        const controlId = `${tableName}__${dataIndex}__${renderer}__${rowIndex}`
        const cellTd = document.getElementById(`${controlId}__td`)
        if (cellTd) {
            cellTd.className = twMerge(cellTd.className, tdClass, p)
            cellTd.style.cssText = tdStyleString
            cellTd.title = tdTooltip
            cellTd.onmousemove = () => {
                // console.log('onmousemove', e)
                if (applyOnMouseMoveScript) {
                    applyOnMouseMoveScript()
                }
            }
        }

        const cellDiv = document.getElementById(`${controlId}__div`)
        if (cellDiv) {
            // console.log('cellDiv', cellId, truncate, divClass, cellDiv.className)
            cellDiv.className = twMerge(truncate, divClass, cellDiv.className)
            cellDiv.style.cssText = `${divStyleString}`
            cellDiv.title = divTooltip

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
                // console.log('applyPostRenderScript', column.renderer, applyPostRenderScript)
                applyPostRenderScript()
                applyOnChangeScript()
                // applyOnMouseMoveScript()

                if (animationDelay) cellDiv.classList.add('visible')
            }, Math.random() * (tableConfig.animationDelay || 0))
        }
    })
}
