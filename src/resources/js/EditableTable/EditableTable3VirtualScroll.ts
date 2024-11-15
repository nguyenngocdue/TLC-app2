import { applyRenderedTRow } from './EditableTable3ApplyRenderedTRow'
import { TbodyTr } from './EditableTable3TBodyTRow'
import { VirtualScrollParams } from './Type/EditableTable3ConfigType'
import { LengthAware } from './Type/EditableTable3DataLineType'
import { TableParams } from './Type/EditableTable3ParamType'

export const updateVisibleRows = (
    virtualTable: HTMLTableElement,
    dataSource: LengthAware,
    tableParams: TableParams,
    virtualScroll?: VirtualScrollParams,
) => {
    console.log('updateVisibleRows')
    const { rowHeight = 57, bufferSize = 5, viewportHeight = 640 } = virtualScroll || {}
    const data = dataSource.data

    //bufferSize * 2: buffer for top and bottom
    const visibleRowCount = Math.ceil(viewportHeight / rowHeight) + bufferSize * 2

    const tableContainer = virtualTable.parentElement as HTMLElement
    tableContainer.style.height = `${viewportHeight}px` // Set container height
    tableContainer.style.overflowY = 'auto'
    tableContainer.setAttribute('data-virtual-row-height', `${rowHeight}`)
    tableContainer.setAttribute('data-buffer-size', `${bufferSize}`)
    tableContainer.setAttribute('data-viewport-height', `${viewportHeight}`)
    tableContainer.setAttribute('data-visible-row-count', `${visibleRowCount}`)

    const scrollTop = tableContainer.scrollTop
    const totalRows = data.length

    // DOM elements
    const spacerTop = document.createElement('tr')
    spacerTop.id = 'spacer-top'
    const startIdx = Math.max(0, Math.floor(scrollTop / rowHeight) - bufferSize)
    spacerTop.style.height = `${startIdx * rowHeight}px`
    spacerTop.setAttribute('data-hidden-rows', `${startIdx}`)

    const spacerBottom = document.createElement('tr')
    spacerBottom.id = 'spacer-bottom'
    const endIdx = Math.min(totalRows, startIdx + visibleRowCount)
    spacerBottom.style.height = `${(totalRows - endIdx) * rowHeight}px`
    spacerBottom.setAttribute('data-hidden-rows', `${totalRows - endIdx}`)

    // const visibleRows = document.getElementById('visible-rows') as HTMLTableRowElement

    // Render the visible rows
    const slicedData = data.slice(startIdx, endIdx)

    const visibleRows = slicedData
        .map((row, mapIndex) => {
            console.log('Making row', row)
            const emptyTr = new TbodyTr(tableParams, row, startIdx + mapIndex).render().outerHTML
            // // const rowStr = applyRenderedTRow(tableParams, row, index)
            console.log(emptyTr)
            return emptyTr
        })
        .join('')
    const result = `${spacerTop.outerHTML}${visibleRows}${spacerBottom.outerHTML}`
    const tbodyElement = virtualTable.querySelector('tbody') as HTMLTableSectionElement
    tbodyElement.innerHTML = result
    slicedData.forEach((row, index) => {
        applyRenderedTRow(tableParams, row, startIdx + index)
    })
}
