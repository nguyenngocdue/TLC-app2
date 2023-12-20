import { getFixedClass } from "../FrozenColumn"

import { ETRNo } from "../Renderers/ETRNo"
import { ETRText } from "../Renderers/ETRText"
import { ETRAction } from "../Renderers/ETRAction"
import { ETRToggle } from "../Renderers/ETRToggle"
import { ETRDropdown } from "../Renderers/ETRDropdown"
import { ETRPicker } from "../Renderers/ETRPicker"

import { ETCText } from "../Controls/ETCText"
import { ETCToggle } from "../Controls/ETCToggle"
import { ETCPicker } from "../Controls/ETCPicker"
import { ETCDropdown } from "../Controls/ETCDropdown"

const getRenderer = (column, cell) => {
    const { renderer } = column
    switch (renderer) {
        case '_no_':
            return ETRNo(cell, column)
        case 'action':
            return ETRAction(cell, column)
        case 'text':
            return ETRText(cell, column) + ETCText(cell, column)
        case 'toggle':
            return ETRToggle(cell, column) + ETCToggle(cell, column)
        case 'dropdown':
            return ETRDropdown(cell, column) + ETCDropdown(cell, column)
        case 'picker':
            return ETRPicker(cell, column) + ETCPicker(cell, column)
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