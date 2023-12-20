import { ETNo } from "../Controls/ETNo"
import { ETText } from "../Controls/ETText"
import { ETAction } from "../Controls/ETAction"
import { ETToggle } from "../Controls/ETToggle"

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

export const TableCell = (params, cell, row, column) => {
    const { hidden } = column
    // if (hidden) return ''
    const renderer = getRenderer(column, cell)
    return `<td class="border overflow-hidden1 ${hidden ? 'hidden' : ''}" >${renderer}</td>`
}