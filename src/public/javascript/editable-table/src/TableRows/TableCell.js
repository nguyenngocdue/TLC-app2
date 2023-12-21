import { getFixedClass } from "../FrozenColumn"
import { getRenderer } from "../Renderers/Renderers"

export const TableCell = (params, cell, row, column, index) => {
    const { tableParams, settings } = params
    const { tableId } = tableParams
    const { tbody_tr_td } = settings.cssClass
    const { hidden, width = 100, dataIndex, control } = column
    const rendererStr = getRenderer(column, cell)
    const fixedClass = getFixedClass(column, index, 'td', tableId)
    const styleStr = `style="width:${width}px"`

    const editable = (control) ? `editable-cell-${tableId}` : ""
    const tabIndex = (control) ? `tabindex="0"` : ''
    const hiddenStr = hidden ? 'hidden' : ''
    const classNames = `${tbody_tr_td} ${editable} ${fixedClass} ${hiddenStr}`.replace(/\s+/g, ' ').trim()
    return `<td ${tabIndex} ${styleStr}
            dataIndex="${dataIndex}"
            class="${classNames}">${rendererStr}</td>`
}