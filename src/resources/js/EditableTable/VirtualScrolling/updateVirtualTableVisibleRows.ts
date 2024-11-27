import { applyRenderedTRow } from '../EditableTable3ApplyRenderedTRow'
import { TbodyTr } from '../EditableTable3TBodyTRow'
// import { VirtualScrollParams } from '../Type/EditableTable3ConfigType'
import { LengthAware } from '../Type/EditableTable3DataLineType'
import { Caller, TableParams } from '../Type/EditableTable3ParamType'
import { applyFixedColumnWidth } from '../FixedColumn/EditableTable3FixedColumn'
import { applySortableRow } from '../SortableRow/EditableTable3SortableRows'
import { TableColumn } from '../Type/EditableTable3ColumnType'
import { reValueOrderNoColumn } from '../SortableRow/EditableTable3OrderableColumn'

declare let tableColumns: { [tableName: string]: TableColumn[] }

const bufferSize = 5
export const lastRenderedStartIdx: { [tableName: string]: number } = {}
const lastRenderedEndIdx: { [tableName: string]: number } = {}
export const visibleRowIds: { [tableName: string]: Set<string> } = {} // Use a Set to track currently visible row IDs

const updateSpacer = (id: string, height: number, hiddenRows: number, scrollTop: number) => {
    const spacer = document.querySelector(id) as HTMLTableRowElement
    if (!spacer) {
        console.error(`${id} not found - virtual table failed to render`)
        return
    }
    spacer.style.height = `${height}px`
    spacer.setAttribute('data-hidden-rows', `${hiddenRows}`)
    spacer.setAttribute('data-scroll-top', `${scrollTop}`)
}

const calculateLoadRemoveIndices = (
    tableName: string,
    startIdx: number,
    endIdx: number,
    isScrollingDown: boolean,
) => {
    const indices = {
        toBeLoaded: { startIdx: 0, endIdx: 0 },
        toBeRemoved: { startIdx: 0, endIdx: 0 },
    }

    if (isScrollingDown) {
        if (lastRenderedStartIdx[tableName] < startIdx) {
            indices.toBeRemoved.startIdx = lastRenderedStartIdx[tableName]
            indices.toBeRemoved.endIdx = startIdx
        }
        if (lastRenderedEndIdx[tableName] < endIdx) {
            indices.toBeLoaded.startIdx = lastRenderedEndIdx[tableName]
            indices.toBeLoaded.endIdx = endIdx
        }
    } else {
        if (lastRenderedStartIdx[tableName] > startIdx) {
            indices.toBeLoaded.startIdx = startIdx
            indices.toBeLoaded.endIdx = lastRenderedStartIdx[tableName]
        }
        if (lastRenderedEndIdx[tableName] > endIdx) {
            indices.toBeRemoved.startIdx = endIdx
            indices.toBeRemoved.endIdx = lastRenderedEndIdx[tableName]
        }
    }

    return indices
}

export const renderOneEmptyRow = (tableParams: TableParams, rowIdx: number, caller: string) => {
    const trId = `${tableParams.tableName}__${rowIdx}`

    let needToCheckRendered = false
    switch (caller) {
        case Caller.ON_SCROLLING:
        case Caller.ON_CLICK_ADD_AN_ITEM:
            needToCheckRendered = true
            break
        case Caller.APPLY_SORTABLE_ROW:
        default:
            needToCheckRendered = false
            break
    }
    if (needToCheckRendered) {
        if (visibleRowIds[tableParams.tableName].has(`${trId}`)) {
            // if (false)
            console.log('row already visible', trId, caller)
            return null
        }
        visibleRowIds[tableParams.tableName].add(`${trId}`)
    }

    // console.log(`renderOneEmptyRow ${rowIdx}`, caller)

    return new TbodyTr(tableParams, rowIdx).render()
}

export const removeOneRow = (tableParams: TableParams, rowIdx: number) => {
    const tableName = tableParams.tableName
    const rowId = `${tableName}__${rowIdx}`
    const hasInSet = visibleRowIds[tableName].has(rowId)

    // Remove only if the row is currently visible
    if (hasInSet) {
        const row = document.querySelector(`#${rowId}`)
        if (row) row.remove()
        // console.log('row removed', rowId)
        visibleRowIds[tableName].delete(rowId) // Remove the ID from the visibleRowIds Set
        // console.log('removing', rowId, 'to', visibleRowIds)
    }
}

export const renderRows = (
    data: any[],
    indices: { startIdx: number; endIdx: number },
    tableParams: TableParams,
    visibleRowCount: number,
    position: 'top' | 'bottom',
) => {
    if (indices.startIdx == indices.endIdx) return
    const tableName = tableParams.tableName
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

    // Generate the rows using `renderOneRow`
    const renderedRows = slicedData
        .map((_, idx) => {
            const rowIndex = indices.startIdx + idx
            const row = renderOneEmptyRow(tableParams, rowIndex, Caller.ON_SCROLLING)
            if (!row) return ''
            return row.outerHTML
        })
        .join('')

    // Insert the rendered rows into the table
    const spacerId = `#${tableName} tbody>tr#spacer-${position}`

    position === 'bottom' ? $(spacerId).before(renderedRows) : $(spacerId).after(renderedRows)

    // Ensure `applyRenderedTRow` is called after the rows are attached to the DOM
    slicedData.forEach((row, idx) => {
        const rowElement = document.getElementById(`${tableName}__${indices.startIdx + idx}`)
        if (rowElement) {
            applyRenderedTRow(tableParams, row, indices.startIdx + idx)
        }
    })
}

const removeRows = (tableParams: TableParams, indices: { startIdx: number; endIdx: number }) => {
    if (indices.startIdx == indices.endIdx) return
    for (let i = indices.startIdx; i < indices.endIdx; i++) {
        removeOneRow(tableParams, i)
    }
}

export const updateVisibleRows = (
    virtualTable: HTMLTableElement,
    dataSource: LengthAware,
    tableParams: TableParams,
    firstLoad = false,
) => {
    const { tableName, tableConfig } = tableParams

    const columns = tableColumns[tableName]
    const viewportHeight = tableConfig.maxH || 640
    const rowHeight = tableConfig.rowHeight || 45
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
    // console.log('scrollTop', scrollTop, virtualTable)
    const startIdx = Math.max(0, Math.floor(scrollTop / rowHeight) - bufferSize)
    const endIdx = Math.min(totalRows, startIdx + visibleRowCount)
    // console.log('startIdx', startIdx, 'endIdx', endIdx)

    // if (startIdx === lastRenderedStartIdx && endIdx === lastRenderedEndIdx) {
    //     return // Skip if no changes
    // }

    // if (startIdx == lastRenderedStartIdx[tableName]) {
    // return
    // }

    const isScrollingDown = startIdx > lastRenderedStartIdx[tableName]
    const indices = calculateLoadRemoveIndices(tableName, startIdx, endIdx, isScrollingDown)

    updateSpacer(`#${tableName} tbody>tr#spacer-top`, startIdx * rowHeight, startIdx, scrollTop)
    updateSpacer(
        `#${tableName} tbody>tr#spacer-bottom`,
        (totalRows - endIdx) * rowHeight,
        totalRows - endIdx,
        scrollTop,
    )

    if (firstLoad) {
        const toBeLoaded = { startIdx: 0, endIdx: visibleRowCount }
        renderRows(data, toBeLoaded, tableParams, visibleRowCount, 'bottom')
    } else {
        if (isScrollingDown) {
            renderRows(data, indices.toBeLoaded, tableParams, visibleRowCount, 'bottom')
            removeRows(tableParams, indices.toBeRemoved)
        } else {
            renderRows(data, indices.toBeLoaded, tableParams, visibleRowCount, 'top')
            removeRows(tableParams, indices.toBeRemoved)
        }
    }

    if (tableConfig.orderable) {
        applySortableRow(tableParams)
        reValueOrderNoColumn(tableParams)
    }
    applyFixedColumnWidth(tableName, columns)

    lastRenderedStartIdx[tableName] = startIdx
    lastRenderedEndIdx[tableName] = endIdx
}
