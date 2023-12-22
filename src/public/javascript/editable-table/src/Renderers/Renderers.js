import { getCurrentValue } from "../Controls/Controls"

import { ETRNo } from "./ETRNo"
import { ETRText } from "./ETRText"
import { ETRAction } from "./ETRAction"
import { ETRToggle } from "./ETRToggle"
import { ETRDropdown } from "./ETRDropdown"
import { ETRDropdownMulti } from "./ETRDropdownMulti"
import { ETRPicker } from "./ETRPicker"
import { ETRPickerDate } from "./ETRPickerDate"
import { ETRPickerTime } from "./ETRPickerTime"

export const getRenderer = (column, dataSourceIndex, tableId) => {
    const { renderer, dataIndex } = column
    // const dataSourceIndex = row.id
    const cell = getCurrentValue(tableId, dataIndex, dataSourceIndex)
    // console.log(tableId, dataIndex, dataSourceIndex)
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
        case 'dropdown_multi':
            // console.log(cell)
            return ETRDropdownMulti(cell, column)
        case 'picker':
            return ETRPicker(cell, column)
        case 'picker_date':
            return ETRPickerDate(cell, column)
        case 'picker_time':
            return ETRPickerTime(cell, column)
        case undefined:
            return undefined
        default:
            return `Unknown renderer [${renderer}]`
    }
}