
import { ETRNo } from "./ETRNo"
import { ETRText } from "./ETRText"
import { ETRAction } from "./ETRAction"
import { ETRToggle } from "./ETRToggle"
import { ETRDropdown } from "./ETRDropdown"
import { ETRPicker } from "./ETRPicker"
import { getCurrentValue } from "../Controls/Controls"

export const getRenderer = (column, dataSourceIndex, tableId) => {
    const { renderer, dataIndex } = column
    // const dataSourceIndex = row.id
    const cell = getCurrentValue(tableId, dataIndex, dataSourceIndex)
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
            return ETRDropdown(cell, { ...column })
        case 'dropdown_multi':
            return ETRDropdown(cell, { ...column, multiple: true })
        case 'picker':
            return ETRPicker(cell, column)
        case undefined:
            return cell
        default:
            return `Unknown renderer [${renderer}]`
    }
}