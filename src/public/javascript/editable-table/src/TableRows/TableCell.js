import { getControl, getCurrentValue } from "../Controls/Controls"
import { getFixedClass } from "../FrozenColumn"
import { getRenderer } from "../Renderers/Renderers"

export const TableCell = (params, row, column, index) => {
    const { tableParams, settings } = params
    const { tableId } = tableParams
    const { tbody_tr_td } = settings.cssClass
    const { hidden, width = 100, dataIndex, control, renderer } = column
    const { id: dataSourceIndex } = row

    let rendererStr = getRenderer(column, dataSourceIndex, tableId)
    if (rendererStr === undefined && control) {
        const currentValue = getCurrentValue(tableId, dataIndex, dataSourceIndex)
        const controlId = `${tableId}_${dataIndex}_${dataSourceIndex}`
        rendererStr = getControl({ column, controlId, currentValue, dataSourceIndex })
        // console.log(currentValue, controlId, rendererStr)
    }
    const fixedClass = getFixedClass(column, index, 'td', tableId)
    const styleStr = `style="width:${width}px"`

    const editable = (control) ? `editable-cell-${tableId}` : ""
    const tabIndex = (control && renderer) ? `tabindex="0"` : ''
    const hiddenStr = hidden ? 'hidden' : ''
    const classNames = `${tbody_tr_td} ${editable} ${fixedClass} ${hiddenStr}`.replace(/\s+/g, ' ').trim()
    return `<td ${tabIndex} ${styleStr}
            data-index="${dataIndex}"
            datasource-index="${dataSourceIndex}"
            class="${classNames}">${rendererStr}</td>`
}