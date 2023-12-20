import { getFixedClass } from "../FrozenColumn"

import { ETRNo } from "../Renderers/ETRNo"
import { ETRText } from "../Renderers/ETRText"
import { ETRAction } from "../Renderers/ETRAction"
import { ETRToggle } from "../Renderers/ETRToggle"
import { ETRDropdown } from "../Renderers/ETRDropdown"
import { ETRPicker } from "../Renderers/ETRPicker"

const getRenderer = (column, cell) => {
    const { renderer } = column
    switch (renderer) {
        case '_no_':
            return ETRNo(cell, column)
        case 'action':
            return ETRAction(cell, column)
        case 'text':
            return ETRText(cell, column)
        case 'toggle':
            return ETRToggle(cell, column)
        case 'dropdown':
            return ETRDropdown(cell, column)
        case 'picker':
            return ETRPicker(cell, column)
        case undefined:
            return cell
        default:
            return `Unknown renderer [${renderer}]`
    }
}

export const TableCell = (params, cell, row, column, index) => {
    const { tableParams } = params
    const { tableId } = tableParams
    const { hidden, width = 100, control } = column
    const rendererStr = getRenderer(column, cell)
    const fixedClass = getFixedClass(column, index, 'td', tableId)
    const styleStr = `style="width:${width}px"`

    const editable = (control) ? `editable-cell-${tableId}` : ""
    const tabIndex = control ? `tabindex="0"` : ''
    return `<td ${tabIndex} class="border p-1  ${editable} ${fixedClass} ${hidden ? 'hidden' : ''}" ${styleStr} >${rendererStr}</td>`
}