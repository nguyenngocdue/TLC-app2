import { TableRenderedValueObject } from '../../Type/EditableTable3DataLineType'
import { Renderer4View } from '../Renderer4View'

export class ActionBox extends Renderer4View {
    render(): TableRenderedValueObject {
        const { tableConfig } = this
        const classList = tableConfig.classList?.button
        // const column = this.column as TableColumnIdLink
        // const entityName = column.rendererAttrs?.entityName || '/dashboard/unknown-entity'

        const iconMoveUp = `<i class="fa fa-arrow-up text-xs"></i>`
        const iconMoveDown = `<i class="fa fa-arrow-down text-xs"></i>`
        const iconCopy = `<i class="fa fa-copy text-xs"></i>`
        const iconTrash = `<i class="fa fa-trash text-xs"></i>`

        const btnMoveUp = `<button class="${classList} px-1.5 py-0.5 rounded bg-blue-500 hover:bg-blue-700 text-white mr-0.5" title="Move Up">${iconMoveUp}</button>`
        const btnMoveDown = `<button class="${classList} px-1.5 py-0.5 rounded bg-blue-500 hover:bg-blue-700 text-white mr-0.5" title="Move Down">${iconMoveDown}</button>`
        const btnCopy = `<button class="${classList} px-1.5 py-0.5 rounded bg-blue-500 hover:bg-blue-700 text-white mr-0.5" title="Copy">${iconCopy}</button>`
        const btnTrash = `<button class="${classList} px-1.5 py-0.5 rounded bg-red-500 hover:bg-red-700 text-white mr-0.5" title="Delete">${iconTrash}</button>`

        const actionBox = `<div class="flex justify-center">
            ${btnMoveUp} ${btnMoveDown} ${btnCopy} ${btnTrash}
        </div>`
        // const href = `${entityName}/${this.cellValue}`

        return {
            rendered: `${actionBox}`,
            tdClass: `text-center`,
        }
    }
}
