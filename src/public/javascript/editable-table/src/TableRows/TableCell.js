import { ETNo } from "../Controls/ETNo"
import { ETText } from "../Controls/ETText"
import { ETAction } from "../Controls/ETAction"
import { ETToggle } from "../Controls/ETToggle"
import { getFixedClass } from "../FrozenColumn"

const getRenderer = (column, cell) => {
    const { renderer } = column
    switch (renderer) {
        case '_no_':
            return ETNo(cell)
        case 'action':
            return ETAction(cell)
        case 'text':
            return ETText(cell)
        case 'toggle':
            return ETToggle(cell)
        case undefined:
            return cell
        default:
            return `Unknown renderer [${renderer}]`
    }
}

export const TableCell = (params, cell, row, column, index) => {
    const { tableParams } = params
    const { tableId } = tableParams
    const { hidden, width = 100 } = column
    // if (hidden) return ''
    const renderer = getRenderer(column, cell)
    const fixedClass = getFixedClass(column, index, 'td', tableId)
    const styleStr = `style="width:${width}px"`
    return `<td class="border p-1 ${fixedClass} ${hidden ? 'hidden' : ''}" ${styleStr} >${renderer}</td>`
}