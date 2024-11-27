import { onDuplicateAnItem } from '../../ControlButtonGroup/onClickDuplicateAnItem'
import { onClickTrashAnItem } from '../../ControlButtonGroup/onClickTrashAnItem'
import { TableRenderedValueObject } from '../../Type/EditableTable3DataLineType'
import { Renderer4View } from '../Renderer4View'

export class ActionColumn extends Renderer4View {
    applyPostRenderScript(): void {
        const { tableParams, rowIndex } = this
        const { tableName } = tableParams

        const idDup = `#${tableName} button#btnDuplicate__${rowIndex}`
        const idTrash = `#${tableName} button#btnTrash__${rowIndex}`

        const btnCopy = document.querySelector(idDup)
        const btnTrash = document.querySelector(idTrash)

        if (btnCopy) {
            btnCopy.addEventListener('click', () => {
                onDuplicateAnItem(tableParams, rowIndex)
            })
        }

        if (btnTrash) {
            btnTrash.addEventListener('click', () => {
                onClickTrashAnItem(tableParams, rowIndex)
            })
        }
    }

    render(): TableRenderedValueObject {
        const { tableConfig, rowIndex } = this
        const classList = tableConfig.classList?.button

        const iconCopy = `<i class="fa fa-copy text-xs"></i>`
        const iconTrash = `<i class="fa fa-trash text-xs"></i>`

        const btnCopy = `<button id="btnDuplicate__${rowIndex}" type="button" class="${classList} px-1.5 py-0.5 rounded bg-blue-500 hover:bg-blue-700 text-white" title="Duplicate">${iconCopy}</button>`
        const btnTrash = `<button id="btnTrash__${rowIndex}" type="button" class="${classList} px-1.5 py-0.5 rounded bg-red-500 hover:bg-red-700 text-white" title="Delete">${iconTrash}</button>`

        const rendered = `
        ${tableConfig.duplicatable ? btnCopy : ''}
        ${tableConfig.deletable ? btnTrash : ''}
        `

        const actionBox = `<div class="flex justify-center gap-0.5">${rendered}</div>`

        return {
            rendered: `${actionBox}`,
            tdClass: `text-center`,

            applyPostRenderScript: this.applyPostRenderScript.bind(this),
        }
    }
}
