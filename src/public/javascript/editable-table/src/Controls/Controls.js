
import { ETCText } from "./ETCText"
import { ETCToggle } from "./ETCToggle"
import { ETCDropdown } from "./ETCDropdown"
import { ETCPicker } from "./ETCPicker"

export const getControl = (column, cell) => {
    const { renderer } = column
    switch (renderer) {
        case 'toggle':
        // return ETCToggle(cell, column)
        case 'dropdown':
        // return ETCDropdown(cell, column)
        case 'picker':
        // return ETCPicker(cell, column)
        case 'text':
            return ETCText(cell, column)
        case undefined:
            return cell
        default:
            return `Unknown renderer [${renderer}]`
    }
}