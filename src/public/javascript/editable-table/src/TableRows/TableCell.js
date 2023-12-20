import { ETNo } from "../Controls/ETNo"
import { ETText } from "../Controls/ETText"
import { ETAction } from "../Controls/ETAction"

const getRenderer = (column, cell) => {
    const { renderer } = column
    switch (renderer) {
        case '_no_':
            return ETNo(cell)
        case 'action':
            return ETAction(cell)
        case 'text':
            return ETText(cell)
        case undefined:
            return cell
        default:
            return `Unknown renderer [${renderer}]`
    }
}

export const TableCell = (params, cell, row, column) => {
    const renderer = getRenderer(column, cell)
    return `<td class="border">${renderer}</td>`
}