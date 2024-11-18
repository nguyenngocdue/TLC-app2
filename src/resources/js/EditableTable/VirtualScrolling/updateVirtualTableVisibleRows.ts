import { applyRenderedTRow } from '../EditableTable3ApplyRenderedTRow'
import { TbodyTr } from '../EditableTable3TBodyTRow'
import { VirtualScrollParams } from '../Type/EditableTable3ConfigType'
import { LengthAware } from '../Type/EditableTable3DataLineType'
import { TableParams } from '../Type/EditableTable3ParamType'

let lastRenderedStartIdx = 0
let lastRenderedEndIdx = 0
const visibleRowIds = new Set<string>() // Use a Set to track currently visible row IDs

const updateSpacer = (id: string, height: number, hiddenRows: number) => {
    const spacer = document.querySelector(id) as HTMLTableRowElement
    if (!spacer) {
        console.error(`${id} not found - virtual table failed to render`)
        return
    }
    spacer.style.height = `${height}px`
    spacer.setAttribute('data-hidden-rows', `${hiddenRows}`)
}

const calculateLoadRemoveIndices = (startIdx: number, endIdx: number, isScrollingDown: boolean) => {
    const indices = {
        toBeLoaded: { startIdx: 0, endIdx: 0 },
        toBeRemoved: { startIdx: 0, endIdx: 0 },
    }

    if (isScrollingDown) {
        if (lastRenderedStartIdx < startIdx) {
            indices.toBeRemoved.startIdx = lastRenderedStartIdx
            indices.toBeRemoved.endIdx = startIdx
        }
        if (lastRenderedEndIdx < endIdx) {
            indices.toBeLoaded.startIdx = lastRenderedEndIdx
            indices.toBeLoaded.endIdx = endIdx
        }
    } else {
        if (lastRenderedStartIdx > startIdx) {
            indices.toBeLoaded.startIdx = startIdx
            indices.toBeLoaded.endIdx = lastRenderedStartIdx
        }
        if (lastRenderedEndIdx > endIdx) {
            indices.toBeRemoved.startIdx = endIdx
            indices.toBeRemoved.endIdx = lastRenderedEndIdx
        }
    }

    return indices
}

const renderRows = (
    data: any[],
    indices: { startIdx: number; endIdx: number },
    tableParams: TableParams,
    visibleRowCount: number,
    position: 'top' | 'bottom',
) => {
    let slicedData = data.slice(indices.startIdx, indices.endIdx)

    // Adjust the sliced data based on scrolling direction and visibleRowCount
    if (slicedData.length > visibleRowCount) {
        if (position === 'bottom') {
            // Scrolling down, keep only the last visibleRowCount items
            slicedData = slicedData.slice(-visibleRowCount)
            indices.startIdx = indices.endIdx - visibleRowCount
        } else {
            // Scrolling up, keep only the first visibleRowCount items
            slicedData = slicedData.slice(0, visibleRowCount)
            indices.endIdx = indices.startIdx + visibleRowCount
        }
    }

    const renderedRows = slicedData
        .map((row, idx) => {
            const rowId = `${tableParams.tableName}__${indices.startIdx + idx}`
            visibleRowIds.add(rowId) // Track newly rendered rows
            // console.log('adding', rowId, 'to', visibleRowIds)
            return new TbodyTr(tableParams, row, indices.startIdx + idx).render().outerHTML
        })
        .join('')

    const spacerId = `#${tableParams.tableName} tbody>tr#spacer-${position}`
    position === 'bottom' ? $(spacerId).before(renderedRows) : $(spacerId).after(renderedRows)

    slicedData.forEach((row, idx) => {
        applyRenderedTRow(tableParams, row, indices.startIdx + idx)
    })
}

const removeRows = (indices: { startIdx: number; endIdx: number }, tableName: string) => {
    if (indices.startIdx == indices.endIdx) return
    // console.log('set size', visibleRowIds.size)
    for (let i = indices.startIdx; i < indices.endIdx; i++) {
        // const rowId = `#${tableName}__${i}`
        // const row = document.querySelector(rowId)
        // if (row) row.remove()
        const rowId = `${tableName}__${i}`
        const hasInSet = visibleRowIds.has(rowId)

        // Remove only if the row is currently visible
        if (hasInSet) {
            const row = document.querySelector(`#${rowId}`)
            if (row) row.remove()
            // console.log('row removed', rowId)
            visibleRowIds.delete(rowId) // Remove the ID from the visibleRowIds Set
            // console.log('removing', rowId, 'to', visibleRowIds)
        }
    }
}

export const updateVisibleRows = (
    virtualTable: HTMLTableElement,
    dataSource: LengthAware,
    tableParams: TableParams,
    virtualScroll?: VirtualScrollParams,
    firstLoad = false,
) => {
    const { rowHeight = 45, bufferSize = 5, viewportHeight = 640 } = virtualScroll || {}
    const data = dataSource.data
    const totalRows = data.length

    const visibleRowCount = Math.ceil(viewportHeight / rowHeight) + bufferSize * 2

    const tableContainer = virtualTable.parentElement as HTMLElement
    tableContainer.style.height = `${viewportHeight}px`
    tableContainer.style.overflowY = 'auto'
    tableContainer.setAttribute('data-virtual-row-height', `${rowHeight}`)
    tableContainer.setAttribute('data-buffer-size', `${bufferSize}`)
    tableContainer.setAttribute('data-viewport-height', `${viewportHeight}`)
    tableContainer.setAttribute('data-visible-row-count', `${visibleRowCount}`)

    const scrollTop = tableContainer.scrollTop
    const startIdx = Math.max(0, Math.floor(scrollTop / rowHeight) - bufferSize)
    const endIdx = Math.min(totalRows, startIdx + visibleRowCount)

    const isScrollingDown = startIdx > lastRenderedStartIdx
    const indices = calculateLoadRemoveIndices(startIdx, endIdx, isScrollingDown)

    updateSpacer(`#${tableParams.tableName} tbody>tr#spacer-top`, startIdx * rowHeight, startIdx)
    updateSpacer(
        `#${tableParams.tableName} tbody>tr#spacer-bottom`,
        (totalRows - endIdx) * rowHeight,
        totalRows - endIdx,
    )

    if (isScrollingDown) {
        renderRows(data, indices.toBeLoaded, tableParams, visibleRowCount, 'bottom')
        removeRows(indices.toBeRemoved, tableParams.tableName)
    } else {
        renderRows(data, indices.toBeLoaded, tableParams, visibleRowCount, 'top')
        removeRows(indices.toBeRemoved, tableParams.tableName)
    }

    if (firstLoad) {
        renderRows(
            data,
            { startIdx: 0, endIdx: visibleRowCount },
            tableParams,
            visibleRowCount,
            'bottom',
        )
    }

    lastRenderedStartIdx = startIdx
    lastRenderedEndIdx = endIdx
}
